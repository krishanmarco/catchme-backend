<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] - Fithancer 1.0 © */

use Slim\Http\UploadedFile;
use Slim\Exception\ApiException;

// Helper for uploading files
// --------------------------------------------------------------------------------------
// $_FILES['file']['name']      The original name of the file on the client machine
// $_FILES['file']['type']      The mime type of the file, eg. "image/gif", needs checking
// $_FILES['file']['size']      The size, in bytes, of the uploaded file
// $_FILES['file']['tmp_name']  Temporary filename of the file in which the uploaded is saved
// $_FILES['file']['error']     Error code associated with this file upload
class FileUploader {

    public static function build(UploadedFile $uploadedFile) {
        return new FileUploader($uploadedFile);
    }


    public function __construct(UploadedFile $uploadedFile) {
        $this->uploadedFile = $uploadedFile;
    }

    /** @var UploadedFile $uploadedFile */
    private $uploadedFile;

    /** @var String $allowedExtRegex */
    private $allowedExtRegex;

    public function allowImages() {
        $this->allowedExtRegex = implode('|', ['jpg','jpe','jpeg','png']);
        return $this;
    }




    public function saveUpload($destinationPath, $destinationName) {

        if ($this->allowedExtRegex != null) {

            $uploadedName = $this->uploadedFile->getClientFilename();
            if (!preg_match('/^.*\.('. $this->allowedExtRegex .')$/i', $uploadedName))
                throw new ApiException(R::return_error_file_upload_failed);

        }


        // Make sure the folder path esists
        if (!is_dir($destinationPath))
            mkdir($destinationPath);

        $fullpath = "{$destinationPath}/{$destinationName}";

        try { $this->uploadedFile->moveTo($fullpath); }
        catch (Exception $e) { throw new ApiException(R::return_error_file_upload_failed, $e); }


        // The upload is success, chmod to
        // make sure the file cannot be executed
        // and only read by the web-server
        if (!chmod($fullpath, 0400)) {
            // If chmod fails, delete the
            // file and return an error
            unlink($fullpath);
            throw new ApiException(R::return_error_file_upload_failed);
        }
    }

}