<?php

namespace App\Policies;

use App\Models\Download;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DownloadPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any downloads.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the download.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Download  $download
     * @return mixed
     */
    public function view(User $user, Download $download)
    {
        return $user->id === $download->user_id;
    }

    /**
     * Determine whether the user can create downloads.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the download.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Download  $download
     * @return mixed
     */
    public function update(User $user, Download $download)
    {
        //
    }

    /**
     * Determine whether the user can delete the download.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Download  $download
     * @return mixed
     */
    public function delete(User $user, Download $download)
    {
        //
    }

    /**
     * Determine whether the user can restore the download.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Download  $download
     * @return mixed
     */
    public function restore(User $user, Download $download)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the download.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Download  $download
     * @return mixed
     */
    public function forceDelete(User $user, Download $download)
    {
        //
    }
}
