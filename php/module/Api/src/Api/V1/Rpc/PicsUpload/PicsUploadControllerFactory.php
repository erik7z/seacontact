<?php
namespace Api\V1\Rpc\PicsUpload;

class PicsUploadControllerFactory
{
    public function __invoke($controllers)
    {
        return new PicsUploadController();
    }
}
