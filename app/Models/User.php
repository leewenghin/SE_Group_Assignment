<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    public $timestamps = true;

    protected $fillable = [
        'email',
        'password',
        'first_name',
        'last_name',
        'gender_id',
        'department_id',
        'avatar_file_path',
        'avatar_file_name',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function HasRole()
    {
        if ($this->user_roles->count() > 0) {
            return true;
        }
        return false;
    }

    private function CountRoleId($roleId)
    {
        $flag = false;
        foreach ($this->user_roles as $ur) {
            if ($ur->role_id == $roleId) {
                $flag = true;
                break;
            }
        }
        return $flag;
    }

    public function IsAdmin()
    {
        return $this->CountRoleId(1);
    }

    public function IsHelpDesk()
    {
        return $this->CountRoleId(2);
    }

    public function IsExecutive()
    {
        return $this->CountRoleId(3);
    }

    public function GetAvatar()
    {
        $img_path = '';
        $img_name = '';

        if ($this->avatar_file_path != null && $this->avatar_file_name != null) {
            $img_path = $this->avatar_file_path;
            $img_name = $this->avatar_file_name;
        }
        else {
            $img_path = "images/";
            if ($this->gender_id == 2) {
                $img_name = 'female_profile.png';
            }
            else {
                $img_name = 'male_profile.png';
            }
        }

        return $img_path.$img_name;
    }





    public function user_roles()
    {
        return $this->hasMany(UserRole::class, 'user_id');
    }

    public function complaints()
    {
        return $this->hasMany(Complaint::class, 'user_id');
    }

    public function complaint_loggings()
    {
        return $this->hasMany(ComplaintLogging::class, 'user_id');
    }

    public function gender()
    {
        return $this->belongsTo(Gender::class, 'gender_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
}
