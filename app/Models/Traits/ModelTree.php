<?php

namespace App\Models\Traits;

trait ModelTree
{
    protected $parentColumn = 'parent_id';

    protected $orderColumn = 'sort';

    protected $queryCallback;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this->hasMany($this, $this->parentColumn);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo($this, $this->parentColumn);
    }

    /**
     * toTree
     *
     * @return array
     */
    public function toTree()
    {
        return $this->buildNestedArray();
    }

    /**
     * tree
     *
     * @param \Closure|null $callback
     * @return array
     */
    public static function tree(\Closure $callback = null)
    {
        return (new static)->withQuery($callback)->toTree();
    }

    /**
     * buildNestedArray
     *
     * @param array $nodes
     * @param int $parentId
     * @return array
     */
    protected function buildNestedArray(array $nodes = [], $parentId = 0)
    {
        $branch = [];

        if (empty($nodes)) {
            $nodes = $this->allNodes();
        }

        foreach ($nodes as $node) {
            if ($node[$this->parentColumn] == $parentId) {
                $children = $this->buildNestedArray($nodes, $node[$this->getKeyName()]);

                if ($children) {
                    $node['children'] = $children;
                }

                $branch[] = $node;
            }
        }

        return $branch;
    }

    /**
     * withQuery
     *
     * @param \Closure|null $query
     * @return $this
     */
    public function withQuery(\Closure $query = null)
    {
        $this->queryCallback = $query;

        return $this;
    }

    /**
     * allNodes
     *
     * @return mixed
     */
    public function allNodes()
    {
        $orderColumn = \DB::getQueryGrammar()->wrap($this->orderColumn);
        $byOrder = $orderColumn.' = 0,'.$orderColumn;

        $self = new static();

        if ($this->queryCallback instanceof \Closure) {
            $self = call_user_func($this->queryCallback, $self);
        }

        return $self->orderByRaw($byOrder)->get()->toArray();
    }
}
