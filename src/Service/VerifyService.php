<?php


namespace App\Service;


use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Twilio\Exceptions\ConfigurationException;
use Twilio\Exceptions\TwilioException;
use Twilio\Rest\Client;

class VerifyService implements VerifyServiceInterface
{
    /**
     * @var Client
     */
    private Client $client;


    /**
     * @var string
     */
    private $verification_sid;


    /**
     * Verification constructor.
     * @param ParameterBagInterface $parameterBag
     * @param $client
     * @param string|null $verification_sid
     * @throws ConfigurationException|TwilioException
     */
    public function __construct(ParameterBagInterface $parameterBag,$client = null, string $verification_sid = null)
    {
        if ($client === null) {
            $sid =$parameterBag->get('ACCOUNT_SID');
            $token = $parameterBag->get('AUTH_TOKEN');
            $client = new Client($sid, $token);
        }
        $this->client = $client;
        //$service = $this->client->verify->v2->services->create("archivage api");
        //$this->verification_sid = $service->sid;
        $this->verification_sid = $verification_sid ?: $parameterBag->get('VERIFY_SID');
    }


    /**
     * Start a phone verification process using Twilio Verify V2 API
     *
     * @param $phone_number
     * @param $channel
     * @return Result
     */
    public function startVerification($phone_number, $channel)
    {
        try {
            $verification = $this->client->verify->v2->services($this->verification_sid)
                ->verifications
                ->create($phone_number, $channel);
            return new Result($verification->sid);
        } catch (TwilioException $exception) {
            return new Result(["Verification failed to start: {$exception->getMessage()}"]);
        }

    }

    /**
     * Check verification code using Twilio Verify V2 API
     *
     * @param $phone_number
     * @param $code
     * @return Result
     */
    public function checkVerification($phone_number, $code)
    {
        try {
            $verification_check = $this->client->verify->v2->services($this->verification_sid)
                ->verificationChecks
                ->create($code, ['to' => $phone_number]);
            if($verification_check->status === 'approved') {
                return new Result($verification_check->sid);
            }
            return new Result(['Verification check failed: Invalid code.']);
        } catch (TwilioException $exception) {
            return new Result(["Verification check failed: {$exception->getMessage()}"]);
        }
    }
}