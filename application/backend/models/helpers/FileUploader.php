<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] - Fithancer 1.0 © */

use Slim\Http\UploadedFile;
use Slim\Exception\Api400;

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

    /** @var int $uploadedFilePermissions */
    private $uploadedFilePermissions = 0406;

    /** @var String $allowedExtRegex */
    private $allowedExtRegex;


    public function isPrivate() {
        $this->uploadedFilePermissions = 0006;
        return $this;
    }

    public function allowOnlyImages() {
        $this->allowedExtRegex = implode('|', ['jpg','jpe','jpeg','png']);
        return $this;
    }


    /**
     * @param $destinationPath
     * @return string: File name (==hash) of the new uploaded file
     * @throws Api400
     */
    public function saveUploadByHash($destinationPath) {
        // Create a new unique name for this file
        $uniqueName = uniqueName();

        // Save the image regularly
        $currentFullPath = $this->saveUpload($destinationPath, $uniqueName);

        // Calculate the file hash
        $filehash = filehash($currentFullPath);

        $nextFullPath = "{$destinationPath}/{$filehash}";

        if (file_exists($nextFullPath)) {
            // This file has already been uploaded
            // delete the new uploaded one
            unlink($currentFullPath);

        } else {
            // This file has never been uploaded
            // rename $currentFullPath to $nextFullPath
            rename($currentFullPath, $nextFullPath);
        }

        return $filehash;
    }


    public function saveUpload($destinationPath, $destinationName) {

        if ($this->allowedExtRegex != null) {

            $uploadedName = $this->uploadedFile->getClientFilename();
            if (!preg_match('/^.*\.('. $this->allowedExtRegex .')$/i', $uploadedName))
                throw new Api400(R::return_error_file_upload_failed);

        }


        // Make sure the folder path exists
        if (!is_dir($destinationPath))
            mkdir($destinationPath, 0777, true);

        $fullpath = "{$destinationPath}/{$destinationName}";

        try {
            $this->uploadedFile->moveTo($fullpath);

            // try resize the image
            try {

                /**
                 * easy image resize function
                 * @param $file - file name to resize
                 * @param $string - The image data as a string, default is null
                 * @param $width - new image width
                 * @param $height - new image height
                 * @param $proportional - keep image proportional, default is no
                 * @param $output - name of the new file (include path if needed)
                 * @param $delete_original - if true the original image will be deleted
                 * @param $use_linux_commands - if set to true will use "rm" to delete the image, if false will use PHP unlink
                 * @param $quality - enter 1-100 (100 is best quality) default is 100
                 * @return boolean|resource
                 */
                $res = smart_resize_image(
                    $fullpath,
                    null,
                    800,
                    800,
                    true,
                    $fullpath . '_',
                    true,
                    false,
                    85
                );

                if ($res) {
                    rename($fullpath . '_', $fullpath);
                }


            } catch (Exception $e) {
                // Not critical error
            }

        }
        catch (Exception $e) {
            throw new Api400(R::return_error_file_upload_failed, $e);
        }


        // The upload is success, chmod to
        // make sure the file cannot be executed
        // and only read by the web-server
        if (!chmod($fullpath, 0406)) {
            // If chmod fails, delete the
            // file and return an error
            unlink($fullpath);
            throw new Api400(R::return_error_file_upload_failed);
        }

        return $fullpath;
    }

}