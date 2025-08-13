<?php

namespace App\Services;

use App\Models\GeneralLog;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class GeneralLogServices
{

    public const CUSTOMER_PAKET_EXPIRED = 'customer_paket_expired';
    public const SEND_CUSTOMER_NOTIFICATION = 'send_customer_notification';
    public const NOTIFIACTION_EXPIRED = 'expired';
    public const NOTIFIACTION_DUEDATE = 'duedate';
    public const NOTIFIACTION_REMINDER_PAYMENT = 'reminder';
    public const CREATE_INVOICE = 'create_invoice';

    public const JOB_PROCESS = 'job_process';
    public const DELETE_CUSTOMER = 'delete_customer';
    public const RESTORE_CUSTOMER = 'restore_customer';
    public const DELETE_CUSTOMER_PAKET = 'delete_customer_paket';
    public const RESTORE_CUSTOMER_PAKET = 'restore_customer_paket';

    /**
     * PAKET
     */
    public const PAKET = 'paket';
    public const DELETE_PAKET = 'delete_paket';
    public const RESTORE_PAKET = 'restore_paket';



    /**
     * Summary of expired
     * @param mixed $customerPaket
     * @param mixed $author
     * @return void
     * General log if customer paket set to expired
     */
    public function expired($customerPaket, $author = 'system')
    {
        $logData = array(
            [
                "user_id" => $customerPaket->user->id,
                "log_history" => $customerPaket->user->full_name . " Set to expired"
            ]
        );
        $input = [
            'log_type' => self::CUSTOMER_PAKET_EXPIRED,
            'log_data' => $logData,
            'author' => $author
        ];
        $this->createLog($input);
    }

    /**
     * General log sending whatsapp notification to customer
     * @param mixed $customerPaket
     * @param mixed $typeNotification
     * @param mixed $author
     * @return void
     */
    public function send_customer_notification($customerPaket, $typeNotification, $author  = 'system')
    {
        switch ($typeNotification) {
            case self::NOTIFIACTION_EXPIRED:
                $logHistory = "Send notification expired to ";
                break;
            case self::NOTIFIACTION_DUEDATE:
                $logHistory = "Send notification duedate payment to ";
                break;
            case self::NOTIFIACTION_REMINDER_PAYMENT:
                $logHistory = "Send notification reminder payment to ";
                break;
            default:
                $logHistory = "Send notification to ";
        }
        $logData = array(
            [
                "user_id" => $customerPaket->user->id,
                // "full_name" => $customerPaket->user->full_name,
                "log_history" => $logHistory . $customerPaket->user->full_name
            ]
        );
        $input = [
            'log_type' => self::SEND_CUSTOMER_NOTIFICATION,
            'log_data' => $logData,
            'author' => $author
        ];
        $this->createLog($input);
    }

    /**
     * General log if created invoice to customer
     * @param mixed $customerPaket
     * @param mixed $periode
     * @param mixed $author
     * @return void
     */
    public function create_invoice($customerPaket, $periode, $author  = 'system')
    {
        $logData = array(
            [
                "user_id" => $customerPaket->user->id,
                // "full_name" => $customerPaket->user->full_name,
                "log_history" => $customerPaket->user->full_name . " Create invoice " . Carbon::parse($periode)->format('F Y')
            ]
        );
        $input = [
            'log_type' => self::CREATE_INVOICE,
            'log_data' => $logData,
            'author' => $author
        ];
        $this->createLog($input);
    }

    public function job_process($jobCommand, $author = 'system')
    {
        $logData = array(
            [
                "log_history" => $jobCommand
            ]
        );
        $input = [
            'log_type' => self::JOB_PROCESS,
            'log_data' => $logData,
            'author' => $author
        ];
        $this->createLog($input);
    }

    public function admin_action($logType, $logHistory, $logHistoryType = null)
    {

        $logData = array(
            [
                'log_history_type' => $logHistoryType,
                "log_history" => $logHistory
            ]
        );
        $input = [
            'log_type' => $logType,
            'log_data' => $logData,
            'author' => Auth::user()->full_name ?? 'system'
        ];
        $this->createLog($input);
    }

    private function createLog($input)
    {
        GeneralLog::create($input);
    }

    /**
     * LOG MIKROTIK PROCESSING
     * This function to generate log processing mikrotik
     */
    public const MIKROTIK = 'mikrotik'; //General Log Type Mikrotik

    //LOG ACTION MIKROTIK
    public const CREATE__PPP_SECRET_MIKROTIK = 'create_ppp_secret';
    public const DELETE_PPP_SECRET_MIKROTIK = 'delete_ppp_secret';
    public const DISABLE_PPP_SECRET_MIKROTIK = 'disable_ppp_secret';
    public const ENABLE_PPP_SECRET_MIKROTIK = 'enable_ppp_secret';

    public const UPDATE_PPP_COMMENT_SECRET_MIKROTIK = 'update_ppp_secret_comment';
    public const UPDATE_PPP_PROFILE_SECRET_MIKROTIK = 'update_ppp_profile_secret';
    public const UPDATE_PPP_SECRET_MIKROTIK = 'update_ppp_secret';

    public const DELETE_MIKROTIK = 'delete_mikrotik';
    public const PERMANENTLY_DELETE_MIKROTIK = 'permanently_delete_mikrotik';
    public const RESTORE_MIKROTIK = 'restore_mikrotik';
    public function log_mikrotik($logAction, $logDescription = null, $message = null, $success)
    {
        $logData = array(
            [
                "log_action" => $logAction,
                "log_history" => $logDescription,
                "message" => $message,
                'success' => $success
            ]
        );
        $input = [
            'log_type' => self::MIKROTIK,
            'log_data' => $logData,
            'author' => Auth::user()->full_name ?? 'system'
        ];
        $this->createLog($input);
    }
}
