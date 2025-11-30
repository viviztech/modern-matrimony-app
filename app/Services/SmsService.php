<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    /**
     * SMS provider (twilio, msg91, aws_sns, log)
     */
    protected string $provider;

    /**
     * Provider credentials
     */
    protected array $config;

    public function __construct()
    {
        $this->provider = config('services.sms.provider', 'log');
        $this->config = config('services.sms.' . $this->provider, []);
    }

    /**
     * Send SMS message.
     */
    public function send(string $phone, string $message): bool
    {
        return match ($this->provider) {
            'twilio' => $this->sendViaTwilio($phone, $message),
            'msg91' => $this->sendViaMsg91($phone, $message),
            'aws_sns' => $this->sendViaAwsSns($phone, $message),
            default => $this->sendViaLog($phone, $message),
        };
    }

    /**
     * Send OTP SMS.
     */
    public function sendOTP(string $phone, string $otp): bool
    {
        $message = "Your verification code is: {$otp}. Valid for 10 minutes. Do not share with anyone.";
        return $this->send($phone, $message);
    }

    /**
     * Send via Twilio.
     */
    protected function sendViaTwilio(string $phone, string $message): bool
    {
        try {
            $accountSid = $this->config['account_sid'] ?? '';
            $authToken = $this->config['auth_token'] ?? '';
            $from Number = $this->config['from_number'] ?? '';

            if (empty($accountSid) || empty($authToken) || empty($fromNumber)) {
                Log::warning('Twilio credentials not configured');
                return $this->sendViaLog($phone, $message);
            }

            $response = Http::withBasicAuth($accountSid, $authToken)
                ->asForm()
                ->post("https://api.twilio.com/2010-04-01/Accounts/{$accountSid}/Messages.json", [
                    'From' => $fromNumber,
                    'To' => $phone,
                    'Body' => $message,
                ]);

            if ($response->successful()) {
                Log::info("SMS sent via Twilio to {$phone}");
                return true;
            }

            Log::error('Twilio SMS failed', [
                'phone' => $phone,
                'response' => $response->json(),
            ]);

            return false;
        } catch (\Exception $e) {
            Log::error('Twilio SMS exception', [
                'phone' => $phone,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Send via MSG91.
     */
    protected function sendViaMsg91(string $phone, string $message): bool
    {
        try {
            $authKey = $this->config['auth_key'] ?? '';
            $senderId = $this->config['sender_id'] ?? '';

            if (empty($authKey) || empty($senderId)) {
                Log::warning('MSG91 credentials not configured');
                return $this->sendViaLog($phone, $message);
            }

            $response = Http::asForm()->post('https://api.msg91.com/api/sendhttp.php', [
                'authkey' => $authKey,
                'mobiles' => $phone,
                'message' => $message,
                'sender' => $senderId,
                'route' => '4', // Transactional
                'country' => '91', // India
            ]);

            if ($response->successful()) {
                Log::info("SMS sent via MSG91 to {$phone}");
                return true;
            }

            Log::error('MSG91 SMS failed', [
                'phone' => $phone,
                'response' => $response->body(),
            ]);

            return false;
        } catch (\Exception $e) {
            Log::error('MSG91 SMS exception', [
                'phone' => $phone,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Send via AWS SNS.
     */
    protected function sendViaAwsSns(string $phone, string $message): bool
    {
        try {
            // Placeholder for AWS SNS implementation
            // Requires AWS SDK: composer require aws/aws-sdk-php

            Log::warning('AWS SNS not implemented, falling back to log');
            return $this->sendViaLog($phone, $message);

            /*
            $sns = new \Aws\Sns\SnsClient([
                'version' => 'latest',
                'region' => $this->config['region'] ?? 'us-east-1',
                'credentials' => [
                    'key' => $this->config['key'] ?? '',
                    'secret' => $this->config['secret'] ?? '',
                ],
            ]);

            $result = $sns->publish([
                'Message' => $message,
                'PhoneNumber' => $phone,
            ]);

            Log::info("SMS sent via AWS SNS to {$phone}");
            return true;
            */
        } catch (\Exception $e) {
            Log::error('AWS SNS exception', [
                'phone' => $phone,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Send via Log (for development/testing).
     */
    protected function sendViaLog(string $phone, string $message): bool
    {
        Log::info('SMS (Log Mode)', [
            'phone' => $phone,
            'message' => $message,
            'timestamp' => now()->toDateTimeString(),
        ]);

        // Also output to console in development
        if (app()->environment('local')) {
            dump("SMS to {$phone}: {$message}");
        }

        return true;
    }

    /**
     * Format phone number (ensure proper format).
     */
    public static function formatPhone(string $phone, string $countryCode = '+91'): string
    {
        // Remove all non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // If phone doesn't start with country code, add it
        if (!str_starts_with($phone, ltrim($countryCode, '+'))) {
            $phone = ltrim($countryCode, '+') . $phone;
        }

        // Add + prefix
        if (!str_starts_with($phone, '+')) {
            $phone = '+' . $phone;
        }

        return $phone;
    }

    /**
     * Validate phone number format.
     */
    public static function validatePhone(string $phone): bool
    {
        // Basic validation - should be 10-15 digits
        $cleaned = preg_replace('/[^0-9]/', '', $phone);
        return strlen($cleaned) >= 10 && strlen($cleaned) <= 15;
    }
}
