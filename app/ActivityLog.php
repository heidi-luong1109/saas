<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'deal_id',
        'log_type',
        'remark',
    ];

    public function user()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }

    public function getRemark()
    {
        $remark = json_decode($this->remark, true);

        if($remark)
        {
            $user = $this->user;

            if($user)
            {
                $user_name = $user->name;
            }
            else
            {
                $user_name = '';
            }
            if($this->log_type == 'Upload File')
            {
                return $user_name . ' ' . __('Upload new file') . ' <b>' . $remark['file_name'] . '</b>';
            }
            elseif($this->log_type == 'Create Task')
            {
                return $user_name . ' ' . __('Create new Task') . " <b>" . $remark['title'] . "</b>";
            }
            elseif($this->log_type == 'Add Product')
            {
                return $user_name . ' ' . __('Add new Products') . " <b>" . $remark['title'] . "</b>";
            }
            elseif($this->log_type == 'Update Sources')
            {
                return $user_name . ' ' . __('Update Sources');
            }
            elseif($this->log_type == 'Create Deal Call')
            {
                return $user_name . ' ' . __('Create new Deal Call');
            }
            elseif($this->log_type == 'Create Deal Email')
            {
                return $user_name . ' ' . __('Create new Deal Email');
            }
            elseif($this->log_type == 'Move')
            {
                return $user_name . " " . __('Moved the deal') . " <b>" . $remark['title'] . "</b> " . __('from') . " " . __(ucwords(!empty($remark['old_status'])?$remark['old_status']:'')) . " " . __('to') . " " . __(ucwords($remark['new_status']));
            }
        }
        else
        {
            return $this->remark;
        }
    }
}
