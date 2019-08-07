<?php

namespace App;

use App\Http\Controllers\Helpers\Helpers;
use App\Models\CheckAssessment;
use App\Models\Role;
use App\Models\UserProfile;
use App\Models\UserType;
use App\Traits\UserRoleTrait;
use App\Traits\PersonalAccessTokenTrait;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\HasApiTokens;
use Laravel\Passport\Passport;
use \Illuminate\Database\Eloquent\Relations\HasMany,
    \Illuminate\Database\Eloquent\Relations\BelongsToMany,
    \Illuminate\Database\Eloquent\Relations\HasOne;
use App\Notifications\UserResetPasswordNotification;


class User extends Authenticatable
{
    use PersonalAccessTokenTrait, HasApiTokens, Notifiable, UserRoleTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'last_name', 'email', 'status', 'image', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public $userInfo = ['imagePath' => '/assets/images/user_profiles/', 'preThumb' => '96x96-'];
    protected static $defaultStatus = ['pending', 'approved', 'disabled'];

    /**
     * @StartActions
     */

    public function hasActions($name): bool
    {
        //the value of an array means user type id
        $actions = [
            'view_check_assessments' => [1, 2, 3],
            'change_check_assessments_status' => [1, 2, 3],
            'save_check_assessments_status_score' => [1, 2, 3],
            'save_check_assessments' => [4, 5],
            'fetch-institutes' => [1, 2, 5],
            'download-export-other' => [1, 2, 3],
        ];
        return (isset($actions[$name]) && in_array($this->userType->type_user_id, $actions[$name], true));
    }

    /**
     * @todo delete user all related user information
     * @return bool
     */
    public function destroyInfo(): bool
    {
        if ($this->image === 'logo.png') {
            return true;
        }
        $deleted = Helpers::removeFile($this->userInfo['imagePath'] . $this->userInfo['preThumb'] . $this->image);
        return $deleted && Helpers::removeFile($this->userInfo['imagePath'] . $this->image);
    }

    /**
     * @todo change user status
     * @param $status
     * @return bool
     */
    public function setStatus($status): bool
    {
        if ($this->status !== $status && in_array($status, self::$defaultStatus, true)) {
            $this->status = $status;
            $this->save();
            //check if status changed to disabled and sign user out
            if ($status === 'disabled') {
                $this->revokeAllValidTokens();
            }
            //end check if status changed to disabled and sign user out
            return true;
        }
        return false;
    }

    /**
     * @EndActions
     */

    /**
     * @Model relationship
     */

    /**@Department */

    /**@Relationship */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public function userType(): HasOne
    {
        return $this->hasOne(UserType::class);
    }

    public function userProfile(): HasOne
    {
        return $this->hasOne(UserProfile::class);
    }

    public function assessments(): HasMany
    {
        return $this->hasMany(CheckAssessment::class);
    }

    public function getTypeOfUserAttribute()
    {
        $user = self::find(Auth::user()->id)->userType;
        if (isset($user)) {
            return $user->typeUser;
        }
        return null;
    }

    public function hasRole($role)
    {
        return $this->roles->pluck('name')->contains($role);
    }

    /**@Relationship */

    public function getTokenName()
    {
        return config('app.name', 'Laravel') . '_trusted_token';
    }

    public function getPersonalTokenExpiresDaysInSeconds(): int
    {
        return Helpers::days_to_seconds(config('auth.days_tokens_expire_in'));
    }

    public function transformUser(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'thumb_image' => $this->userInfo['imagePath'] . $this->userInfo['preThumb'] . $this->image,
            'image' => $this->userInfo['imagePath'] . $this->image,
            'email' => $this->email,
            'last_name' => $this->last_name,
            'type' => base64_encode($this->userType->typeUser->name),
        ];
    }

    public function revokeAllValidTokens()
    {
        $validTokens = Passport::$tokenModel::where('user_id', $this->id)
            ->where('client_id', 1)->where('revoked', 0)
            ->update(['revoked' => 1]);

        return $validTokens;
    }

    /**
     * Send mail to the user who request to forgot password
     * @param string $token
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new UserResetPasswordNotification($token));
    }

}
