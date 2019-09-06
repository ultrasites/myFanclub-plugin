<?php
namespace myfanclub\modules\member\controller;

use myfanclub\core\MyfcCore;
use myfanclub\modules\member\view\MyfcMemberView;
use myfanclub\modules\member\database\MyfcMemberDatabase;
use myfanclub\helper\MyfcFormat;
use myfanclub\core\events\MyfcEventManager;

/**
 * Controller for member administration
 *
 *
 * @package  controller
 * @author   Ultra Sites Medienagentur <info@ultra-sites.de>
 * @version  alpha
 * @access   public
 * @see      http://myfanclub.ultra-sites.de
 */
class MyfcMemberController
{
    /**
     * @var MyfcMemberDatabase $database
     */
    private $database;


    public function __construct()
    {
        $this->database = new MyfcMemberDatabase();
    }

    /**
     * saves a new member
     *
     * @param  $post Data
     * @access public
     */
    public function myfcSaveMember($post)
    {
        $wp_user = $this->database->myfcSelectWpUser($post['intersectionMember']);

        $user = MyfcFormat::myfcSplitDisplayName($wp_user['display_name']);


        $this->database->myfcInsertFanclubMember(
            $post['intersectionMember'],
            $user['lastname'],
            $user['forename'],
            $post['street'],
            $post['housenumber'],
            $post['plz'],
            $post['city'],
            $wp_user['user_email'],
            $post['phone'],
            MyfcFormat::myfcFormatDateToMySQLDate($post['birthday']),
            MyfcFormat::myfcFormatDateToMySQLDate($post['start']),
            $post['paymentSpecial']
        );

        /*
         *  myFanclub - Pro (Module: Mail)
         *
         *  Please visit us: https://myfanclub.ultra-sites.de
         *
         *  Ultra Sites Medienagentur, 2019, https://www.ultra-sites.de
         *
         */
        if (MyfcCore::getInstance()->isModuleRegistered('mail') && $post['sendAdditionalData'] == "on") {
            MyfcEventManager::getInstance()->trigger(new \myfanclub\modules\mail\events\MyfcMailSendMailEvent(), [
                'mailType' => 'entry',
                'subject' => 'Willkommen im Fanclub',
                'name' => $user['forename'],
                'receiverEmail' => $wp_user['user_email'],
                'username' => $wp_user['user_login']
            ]);
        }


        if (MyfcCore::getInstance()->isModuleRegistered('log')) {
            MyfcEventManager::getInstance()->trigger(new \myfanclub\modules\log\events\MyfcAddLogEvent(), [
                'level' => 'info',
                'message' => 'Member saved',
                'data' => json_encode($post).", ".json_encode($user)
            ]);
        }
    }

    /**
     * update an existing member
     *
     * @param  $id of the member
     * @param  $post data
     * @access public
     */
    public function myfcUpdateMember($id, $post)
    {
        $this->database->myfcUpdateMemberData(
            $id,
            $post['forename'],
            $post['lastname'],
            $post['street'],
            $post['housenumber'],
            $post['plz'],
            $post['city'],
            $post['email'],
            $post['phone'],
            $post['birthday'],
            $post['paymentSpecial']
        );

        if (MyfcCore::getInstance()->isModuleRegistered('log')) {
            MyfcEventManager::getInstance()->trigger(new \myfanclub\modules\log\events\MyfcAddLogEvent(), [
                'level' => 'info',
                'message' => 'Member updated',
                'data' => json_encode($post)
            ]);
        }
    }

    /**
     * delete an existing member
     *
     * @param  member id
     * @access public
     */
    public function myfcDeleteMember($post)
    {
        $this->database->myfcDeleteMember($post['id']);

        /*
         *  myFanclub - Pro (Module: Mail)
         *
         *  Please visit us: https://myfanclub.ultra-sites.de
         *
         *  Ultra Sites Medienagentur, 2019, https://www.ultra-sites.de
         *
         */
        if (MyfcCore::getInstance()->isModuleRegistered('mail') && $post['sendAdditionalData'] == "on") {
            $wp_user = $this->database->myfcSelectWpUser($post['id']);
            $user = MyfcFormat::myfcSplitDisplayName($wp_user['display_name']);

            MyfcEventManager::getInstance()->trigger(new \myfanclub\modules\mail\events\MyfcMailSendMailEvent(), [
                'mailType' => 'exit',
                'subject' => 'Dein Austritt aus dem Fanclub',
                'name' => $user['forename'],
                'receiverEmail' => $wp_user['user_email'],
                'username' => $wp_user['user_login']
            ]);
        }

        if (MyfcCore::getInstance()->isModuleRegistered('log')) {
            MyfcEventManager::getInstance()->trigger(new \myfanclub\modules\log\events\MyfcAddLogEvent(), [
                'level' => 'info',
                'message' => 'Member deleted',
                'data' => json_encode($post).", ".json_encode($user)
            ]);
        }
    }

    public function renderExportPdf()
    {

        /**
         * @var MyfcMemberView $view
         */
        $view = new MyfcMemberView($this->database);

        $view->renderExportPdf();
    }

    public function render($request)
    {

        /**
         * @var MyfcMemberView $view
         */
        $view = new MyfcMemberView($this->database);

        $view->render($request);
    }

    public function myfcSendMail($request)
    {
        $mailType = $request['mail'];

        $database = new MyfcMemberDatabase();

        if ($mailType == 'message') {
            $receiverEmailAddress = $database->myfcSelectWpUser($request['id'])['user_email'];

            if ($request['mail'] == 'message') {
                MyfcEventManager::getInstance()->trigger(
                    new \myfanclub\modules\mail\events\MyfcMailSendMailEvent(),
                    [
                        'subject' => $request['mail_subject'],
                        'content' => $request['mail_message'],
                        'mailType' => 'message',
                        'receiverEmail' => $receiverEmailAddress
                    ]
                );
            }
        } elseif ($mailType == 'queue') {
            $members = $database->myfcSelectAllFromTable('ASC');

            foreach ($members as $member) {
                MyfcEventManager::getInstance()->trigger(
                    new \myfanclub\modules\mail\events\MyfcMailSendMailEvent(),
                    [
                            'subject' => $request['mail_subject'],
                            'content' => $request['mail_message'],
                            'mailType' => 'message',
                            'receiverEmail' => $member->email
                        ]
                );
            }
        }

        if (MyfcCore::getInstance()->isModuleRegistered('log')) {
            MyfcEventManager::getInstance()->trigger(new \myfanclub\modules\log\events\MyfcAddLogEvent(), [
                'level' => 'info',
                'message' => 'Mail sent',
                'data' => json_encode($request)
            ]);
        }
    }
}
