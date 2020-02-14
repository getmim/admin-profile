<?php
/**
 * Filter
 * @package admin-profile
 * @version 0.0.1
 */

namespace AdminProfile\Library;

use Profile\Model\Profile;

class Filter implements \Admin\Iface\ObjectFilter
{
    static function filter(array $cond): ?array{
        $cnd = [];
        if(isset($cond['q']) && $cond['q'])
            $cnd['q'] = (string)$cond['q'];
        $profiles = Profile::get($cnd, 15, 1, ['fullname'=>true]);
        if(!$profiles)
            return [];

        $result = [];
        foreach($profiles as $profile){
            $result[] = [
                'id'    => (int)$profile->id,
                'label' => $profile->fullname,
                'info'  => $profile->email,
                'icon'  => NULL
            ];
        }

        return $result;
    }

    static function lastError(): ?string{
        return null;
    }
}