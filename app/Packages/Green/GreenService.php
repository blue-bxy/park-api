<?php


namespace App\Packages\Green;


use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Green\Green;

/**
 * @method \AlibabaCloud\Green\V20180509\AddFaces addFaces(array $options = [])
 * @method \AlibabaCloud\Green\V20180509\AddGroups addGroups(array $options = [])
 * @method \AlibabaCloud\Green\V20180509\AddPerson addPerson(array $options = [])
 * @method \AlibabaCloud\Green\V20180509\AddSimilarityImage addSimilarityImage(array $options = [])
 * @method \AlibabaCloud\Green\V20180509\AddSimilarityLibrary addSimilarityLibrary(array $options = [])
 * @method \AlibabaCloud\Green\V20180509\AddVideoDna addVideoDna(array $options = [])
 * @method \AlibabaCloud\Green\V20180509\AddVideoDnaGroup addVideoDnaGroup(array $options = [])
 * @method \AlibabaCloud\Green\V20180509\DeleteFaces deleteFaces(array $options = [])
 * @method \AlibabaCloud\Green\V20180509\DeleteGroups deleteGroups(array $options = [])
 * @method \AlibabaCloud\Green\V20180509\DeletePerson deletePerson(array $options = [])
 * @method \AlibabaCloud\Green\V20180509\DeleteSimilarityImage deleteSimilarityImage(array $options = [])
 * @method \AlibabaCloud\Green\V20180509\DeleteSimilarityLibrary deleteSimilarityLibrary(array $options = [])
 * @method \AlibabaCloud\Green\V20180509\DeleteVideoDna deleteVideoDna(array $options = [])
 * @method \AlibabaCloud\Green\V20180509\DeleteVideoDnaGroup deleteVideoDnaGroup(array $options = [])
 * @method \AlibabaCloud\Green\V20180509\DetectFace detectFace(array $options = [])
 * @method \AlibabaCloud\Green\V20180509\FileAsyncScan fileAsyncScan(array $options = [])
 * @method \AlibabaCloud\Green\V20180509\FileAsyncScanResults fileAsyncScanResults(array $options = [])
 * @method \AlibabaCloud\Green\V20180509\GetAddVideoDnaResults getAddVideoDnaResults(array $options = [])
 * @method \AlibabaCloud\Green\V20180509\GetFaces getFaces(array $options = [])
 * @method \AlibabaCloud\Green\V20180509\GetGroups getGroups(array $options = [])
 * @method \AlibabaCloud\Green\V20180509\GetPerson getPerson(array $options = [])
 * @method \AlibabaCloud\Green\V20180509\GetPersons getPersons(array $options = [])
 * @method \AlibabaCloud\Green\V20180509\GetSimilarityImage getSimilarityImage(array $options = [])
 * @method \AlibabaCloud\Green\V20180509\GetSimilarityLibrary getSimilarityLibrary(array $options = [])
 * @method \AlibabaCloud\Green\V20180509\ImageAsyncScan imageAsyncScan(array $options = [])
 * @method \AlibabaCloud\Green\V20180509\ImageAsyncScanResults imageAsyncScanResults(array $options = [])
 * @method \AlibabaCloud\Green\V20180509\ImageScanFeedback imageScanFeedback(array $options = [])
 * @method \AlibabaCloud\Green\V20180509\ImageSyncScan imageSyncScan(array $options = [])
 * @method \AlibabaCloud\Green\V20180509\ListSimilarityImages listSimilarityImages(array $options = [])
 * @method \AlibabaCloud\Green\V20180509\ListSimilarityLibraries listSimilarityLibraries(array $options = [])
 * @method \AlibabaCloud\Green\V20180509\SearchPerson searchPerson(array $options = [])
 * @method \AlibabaCloud\Green\V20180509\SetPerson setPerson(array $options = [])
 * @method \AlibabaCloud\Green\V20180509\TextFeedback textFeedback(array $options = [])
 * @method \AlibabaCloud\Green\V20180509\TextScan textScan(array $options = [])
 * @method \AlibabaCloud\Green\V20180509\UploadCredentials uploadCredentials(array $options = [])
 * @method \AlibabaCloud\Green\V20180509\VideoAsyncScan videoAsyncScan(array $options = [])
 * @method \AlibabaCloud\Green\V20180509\VideoAsyncScanResults videoAsyncScanResults(array $options = [])
 * @method \AlibabaCloud\Green\V20180509\VideoCancelScan videoCancelScan(array $options = [])
 * @method \AlibabaCloud\Green\V20180509\VideoFeedback videoFeedback(array $options = [])
 * @method \AlibabaCloud\Green\V20180509\VideoSyncScan videoSyncScan(array $options = [])
 * @method \AlibabaCloud\Green\V20180509\VoiceAsyncScan voiceAsyncScan(array $options = [])
 * @method \AlibabaCloud\Green\V20180509\VoiceAsyncScanResults voiceAsyncScanResults(array $options = [])
 * @method \AlibabaCloud\Green\V20180509\VoiceCancelScan voiceCancelScan(array $options = [])
 * @method \AlibabaCloud\Green\V20180509\VoiceIdentityCheck voiceIdentityCheck(array $options = [])
 * @method \AlibabaCloud\Green\V20180509\VoiceIdentityRegister voiceIdentityRegister(array $options = [])
 * @method \AlibabaCloud\Green\V20180509\VoiceIdentityStartCheck voiceIdentityStartCheck(array $options = [])
 * @method \AlibabaCloud\Green\V20180509\VoiceIdentityStartRegister voiceIdentityStartRegister(array $options = [])
 * @method \AlibabaCloud\Green\V20180509\VoiceIdentityUnregister voiceIdentityUnregister(array $options = [])
 */
class GreenService
{
    protected $key;

    protected $secret;

    protected $region_id;

    public function __construct(string $key, string $secret, string $region_id)
    {
        $this->key = $key;

        $this->secret = $secret;

        $this->region_id = $region_id;
    }

    /**
     * regionId
     *
     * @param string $regionId
     * @return $this
     */
    public function regionId(string $regionId)
    {
        $this->region_id = $regionId;

        return $this;
    }

    /**
     * accessKeyClient
     *
     * @throws \AlibabaCloud\Client\Exception\ClientException
     */
    private function accessKeyClient()
    {
        AlibabaCloud::accessKeyClient($this->key, $this->secret)->asDefaultClient();
    }

    /**
     * @param $name
     * @param $arguments
     *
     * @return mixed
     * @throws \AlibabaCloud\Client\Exception\ClientException
     */
    public function __call($name, $arguments)
    {
        $this->accessKeyClient();

        $green = Green::v20180509()->$name($arguments);

        if (method_exists($green, 'regionId')) {
            $green->regionId($this->region_id);
        }

        return $green;
    }
}
