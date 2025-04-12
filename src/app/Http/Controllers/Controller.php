<?php

namespace App\Http\Controllers;

abstract class Controller
{
    /**
     * Adminタイトル取得
     * 
     * @param string $adminPageTitle
     * @param string $subTitle
     * @return array
     */
    protected function getAdminTitle(string $adminPageTitle, string $subTitle): array
    {
        $pageTitle = $adminPageTitle . ' ' . $subTitle;
        return [
            'pageTitle' => $pageTitle,
            'webTitle' => config('const.title.web_title.admin') . ' | ' . $pageTitle,
        ];
    }

    /**
     * Userタイトル取得
     * 
     * @param string $pageTitle
     * @return array
     */
    protected function getUserTitle(string $pageTitle): array
    {
        return [
            'pageTitle' => $pageTitle,
            'webTitle' => config('const.title.web_title.user') . ' | ' . $pageTitle,
        ];
    }
}
