<?php

namespace App\Http\Controllers\API;

use App\category;
use App\Http\Controllers\API\BaseController as BaseController;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;
use App\Mail\activationmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\notification;
use App\item;
use App\item_image;
use App\rate;
use App\favorite_item;
use App\order;
use App\member;
use Illuminate\Support\Facades\Auth;
use Validator;
use DB;


class itemController extends BaseController
{

     public function category_items(Request $request)
    {
        $lastitems = array();
        
        $allitems  = item::where('category_id',$request->category_id)->where('suspensed',0)->orderBy('id','desc')->get(); 
        if(count($allitems) != 0)
        {
            foreach ($allitems as $item) 
                {  
                    $image     = item_image::where('item_id',$item->id)->first();
                    
                    array_push($lastitems, 
                    array(
                          "id"              => $item->id,
                          'image'           => $image,
                          'title'           => $item->artitle,
                          "type"           => $item->type,
                          "price"           => $item->price,
                          
                          
                        ));
                }
            return $this->sendResponse('success', $lastitems); 
        }
        else 
        {
            $errormessage = ' لا يوجد منتجات في هذا القسم' ;
            return $this->sendError('success',$errormessage);
        }            
    }

    public function allitems(Request $request)
    {
        $lastitems = array();
        
        $allitems  = item::where('suspensed',0)->orderBy('id','desc')->get(); 
        if(count($allitems) != 0)
        {
            foreach ($allitems as $item) 
                {  
                    $image     = item_image::where('item_id',$item->id)->first();
                    
                    array_push($lastitems, 
                    array(
                          "id"              => $item->id,
                          'image'           => $image,
                          'title'           => $item->artitle,
                          "type"           => $item->type,
                          "price"           => $item->price,
                          
                        ));
                }
            return $this->sendResponse('success', $lastitems); 
        }
        else 
        {
            $errormessage = 'لا يوجد منتجات' ;
            return $this->sendError('success',$errormessage);
        }            
    }

    public function showitem(Request $request)
    {
         $showitem = item::find($request->item_id);
         if($showitem)
         {
             $favorititem = favorite_item::where('user_id',$request->user_id)->where('item_id',$showitem->id)->first();
            if($favorititem)
            {
                $favorited =1;
            }else{
                $favorited =0;
            }
            $iteminfo     = array();
            
            $category = category::where('id',$showitem->category_id)->first();
            
            $images    = item_image::where('item_id',$showitem->id)->get();
            
            array_push($iteminfo, 
            array(
                  "id"              => $showitem->id,
                  'title'           => $showitem->artitle,
                  "price"           => $showitem->price,
                  "details"         => $showitem->details,
                  "type"           => $showitem->type,
                  "category"           => $category->name,
                  'images'          => $images,
                  'favorited'       => $favorited,
                ));

               

                
            return $this->sendResponse('success', $iteminfo); 
         }
         else 
         {
            $errormessage = 'المنتج غير موجود' ;
            return $this->sendError('success', $errormessage);   
         }
    }

    // public function addrate(Request $request)
    // {
    //     $userrating = rate::where('user_id',$request->user_id)->where('item_id',$request->item_id)->first();
    //     if($userrating)
    //     {
    //         $errormessage ='تم تقييم هذا المنتج سابقا' ;
    //         return $this->sendError('success', $errormessage);
    //     }
    //     else 
    //     {
    //         $newrate                = new rate();
    //         $newrate->user_id       = $request->user_id;
    //         $newrate->item_id       = $request->item_id;
    //         $newrate->rate          = $request->rate;
    //         $newrate->created_date  = date("Y-m-d");
    //         $newrate->created_time  = date("H:i:s");
    //         $newrate->save();
    //         $errormessage ='تم التقييم بنجاح';
    //         return $this->sendResponse('success', $errormessage);
    //     }
    // }

    public function makefavoriteitem(Request $request)
    {
        $favorited = favorite_item::where('item_id',$request->item_id)->where('user_id',$request->user_id)->first();
        if($favorited)
        {
            $errormessage ='هذا المنتج موجود ف المفضلة';
            return $this->sendError('success', $errormessage); 
        }
        else 
        {
            $newfavad = new favorite_item;
            $newfavad->user_id = $request->user_id;
            $newfavad->item_id   = $request->item_id;
            $newfavad->save();
            $errormessage ='تم اضافة المنتج ف المفضلة بنجاح';
            return $this->sendResponse('success', $errormessage);
        }
    }

    public function cancelfavoriteitem(Request $request)
    {
        favorite_item::where('user_id',$request->user_id)->where('item_id',$request->item_id)->delete();
        $errormessage ='تم حذف المنتج من المفضلة';
        return $this->sendResponse('success', $errormessage);
    }
    
}
