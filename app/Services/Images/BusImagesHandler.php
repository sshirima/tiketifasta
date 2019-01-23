<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 1/22/2019
 * Time: 3:12 PM
 */

namespace App\Services\Images;


use App\Models\BusImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use \Intervention\Image\Facades\Image;

trait BusImagesHandler
{
    public function storeBusImages(Request $request, $bus){
        $reply = ['status'=>false];
        try{
            if($request->hasFile('bus_images')){
                $images = $request->file('bus_images');

                foreach ($images as $key=>$image){
                    $name = $this->storeImage($image, $bus->reg_number.'_'.($key+1));

                    $this->saveImageName($name, $bus);
                }
                $reply['status'] = true;
            }
        }catch (\Exception $ex){
            $reply['error'] = 'error: '.$ex->getMessage();
        }
        return $reply;
    }


    private function storeImage($image, $imageName){
        $imageName = $imageName . '.' . $image->getClientOriginalExtension();

        $imagePath = public_path('images/buses/'. $imageName);

        $this->resizeImage($image,900,600)->save($imagePath);

        return $imageName;
    }

    /**
     * @param $imageName
     * @return bool
     */
    public function deleteImage($imageName){
        //Remove image name from DB
        //$status = $this->deleteProductImage($imageName);
        //Remove image from Storage
        return $this->deleteImageFromDisk(public_path('images/buses/'.$imageName));
    }

    /**
     * @param $imageName
     * @return bool
     */
    private function deleteImageFromDisk($imageName){
        return $status = File::delete($imageName);
    }

    /**
     * @param $imageName
     * @param $bus
     * @return mixed
     */
    private function saveImageName($imageName, $bus){
        return BusImage::create([
            BusImage::COL_NAME=>$imageName,
            BusImage::COL_BUS_ID=>$bus->id
        ]);
    }
    /**
     * @param $image
     * @param $width
     * @param $length
     * @return mixed
     */
    private function resizeImage($image, $width, $length){
        return $this->createImage($image)->resize($width, $length);
    }

    /**
     * @param $image
     * @return mixed
     */
    private function createImage($image){
        return  Image::make($image);
    }
}