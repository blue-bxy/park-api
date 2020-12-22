<?php


namespace App\Models\Users;


interface RoaGreenInterface
{
    public function getContent(): string ;

    public function getContentColumn(): string ;

    public function getImageColumn(): string;

    public function getImages(): array ;
}
