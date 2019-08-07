<?php
/**
 * Created by PhpStorm.
 * User: BeeTimberlake
 * Date: 2/26/2019
 * Time: 10:10 AM
 */

namespace App\Responses\User;


use App\Http\Controllers\Helpers\Helpers;
use App\Organize;
use App\User;
use Illuminate\Contracts\Support\Responsable;
use App\UserProfile;
use Image, File;

class UserProfileManage implements Responsable
{
    protected $req;
    protected $uploadPath = '/assets/images/posts/';

    public function __construct($request = null)
    {
        $this->req = $request;
    }

    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function toResponse($request)
    {
        $data = $request->all();
        if (Helpers::isAjax($request)) {
            $user = $request->user();
            if (isset($user)) {
                //Personal Information
                $user->name = $request->get('first_name');
                $user->last_name = $request->get('last_name');
                $this->saveImageProfile($user);
                $user->save();
                //Personal Information

                //check if admin return back
                if (User::isAdminUser($user)) {
                    return response()->json(['success' => true, 'data' => $data]);
                }
                //check if admin return back
                $hasProfile = true;
                $userProfile = $user->profile;
                if (!isset($userProfile)) {
                    $userProfile = new UserProfile();
                    $hasProfile = false;
                }
                //Marital Information
                if (in_array($request->get('marital_status'), $this->getMaritalStatus(), true)) {
                    $userProfile->marital_status = $request->get('marital_status');
                } else {
                    $userProfile->marital_status = 'none';
                }
                //Marital Information
                //Address Information & Description
                if ($request->has('placeOfBirth')) {
                    $userProfile->place_of_birth = $request->get('placeOfBirth');

                }
                if ($request->has('placeOfResident')) {
                    $userProfile->current_address = $request->get('placeOfResident');;
                }
                if ($request->has('personalDescription')) {
                    $userProfile->description = $request->get('personalDescription');;
                }
                //Address Information & Description

                //Personal Information
                if ($request->has('dateOfBirth')) {
                    $userProfile->date_of_birth = Helpers::toFormatDateString($request->get('dateOfBirth'), 'Y-m-d H:i:s');
                }
                if ($request->has('phone_number')) {
                    if ($request->get('phone_number') !== null && !Helpers::isValidPhoneNumber($request->get('phone_number'))) {
                        return response()->json(['errors' => ['phone_number' => ['Your phone number is invalid.']]], 422);
                    }
                    $userProfile->phone_number = $request->get('phone_number');
                }
                //Personal Information
                //@Save User Profile
                if (!$hasProfile) {
                    $user->profile()->save($userProfile);
                } else {
                    $userProfile->save();
                }
                //@Save User Profile
            }
            return response()->json(['success' => true, 'data' => $data]);
        }
    }

    public function saveImageProfile($user): void
    {
        if ($this->req->hasFile('profile_image')) {
            $file = $this->req->file('profile_image');
            $fileExt = strtolower($file->getClientOriginalExtension());
            $imgOriginalName = Helpers::subFileName($file->getClientOriginalName()) . md5('^');
            $img_filename = $imgOriginalName . md5(date('Y-m-d H:i:s') . microtime()) . time() . '_profile.' . $fileExt;
            $uploadPath = $user->userInfo['imagePath'];
            $preThumb = $user->userInfo['preThumb'];
            if ($fileExt === 'gif' || $fileExt === 'svg') {
                $location = public_path($uploadPath);
                $file->move($location, $img_filename);
                File::copy($location . $img_filename, $location . $preThumb . $img_filename);
            } else {
                //save original
                $img = Image::make($file);
                $location = public_path($uploadPath . $img_filename);
                $img->save($location)->destroy();
                //save original
                //save thumb
                $img = Image::make($file);
                // add callback functionality to retain maximal original image size
                $img->fit(96, 96, function ($constraint) {
                    $constraint->upsize();
                });
                $location = public_path($uploadPath . $preThumb . $img_filename);
                $img->save($location)->destroy();
                //save thumb
            }
            if ($user->image !== 'logo.png') {
                Helpers::removeFile($uploadPath . $preThumb . $user->image);
                Helpers::removeFile($uploadPath . $user->image);
            }
            $user->image = $img_filename;
            $user->save();
        }
    }

    public function getMaritalStatus()
    {
        return ['none', 'single', 'married'];
    }
}
