<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = ['p_name'];

    static public function validatePhotoForAjax($ext)
    {
        $allowedExts = ["jpg","jpeg","png"];

        $ext = self::fileMimeType($ext);

        $ext = strtolower($ext);
        if(!in_array($ext,$allowedExts))
        {
            return array('status' => false, 'message' => 'Invalid photo type, only jpg, jpeg and png are accepted');
        }

        return array('status' => true, 'message' => 'ok');
    }

    public static function fileMimeType($extention) {

        if(!is_null($extention)) {
            switch($extention) {
                case 'plain':
                    return 'txt';
                    break;
                case 'vnd.oasis.opendocument.text':
                    return 'odt';
                    break;
                case 'msword':
                    return 'doc';
                    break;
                case 'vnd.openxmlformats-officedocument.wordprocessingml.document':
                    return 'docx';
                    break;
                case 'jpeg':
                    return 'jpg';
                    break;
                case 'png':
                    return 'png';
                    break;
                case 'pdf':
                    return 'pdf';
                    break;
                default:
                    break;
            }
        }

    }
}
