<?php


namespace App\Service\InfoBip;


use GuzzleHttp\Client;
use Infobip\Api\TfaApi;
use Infobip\ApiException;
use Infobip\Configuration;
use Infobip\Model\TfaApplicationConfiguration;
use Infobip\Model\TfaApplicationRequest;
use Infobip\Model\TfaCreateMessageRequest;
use Infobip\Model\TfaPinType;
use Infobip\Model\TfaResendPinRequest;
use Infobip\Model\TfaStartAuthenticationRequest;
use Infobip\Model\TfaVerifyPinRequest;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ClientInfobip
{
    private $BASE_URL = "https://5vj4ng.api.infobip.com";
    private $API_KEY = "b87dd86e9ae440716a0bec71a5e7a83a-e9408d5c-5f45-4d0e-b823-8413809155ad";
    private Client $client;
    private TfaApi $tfaApi;

    /**
     * ClientInfobip constructor.
     */
    public function __construct(ParameterBagInterface $parameterBag)
    {
        $configuration = (new Configuration())
            ->setHost($parameterBag->get('INFOBIPBASEURL'))
            ->setApiKeyPrefix('Authorization', 'App')
            ->setApiKey('Authorization', $parameterBag->get('INFOBIPAPI'));

        $this->client = new Client();
        $this->tfaApi = new TfaApi($this->client, $configuration);
    }

    public function createAFApplication()
    {
        $tfaApplication = $this->tfaApi->createTfaApplication(
            (new TfaApplicationRequest())->setName("2FA application"));

        return $tfaApplication->getApplicationId();
    }

    public function getAFApplication($appId)
    {
        $options = [
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ]
        ];
        $endpoint = '2fa/2/applications/' . $appId;
        $response = $this->client->get($endpoint, $options);
    }

    public function createAFTemplate($appid)
    {

        $tfaMessageTemplate = $this->tfaApi->createTfaMessageTemplate($appid,
            (new TfaCreateMessageRequest())
                ->setMessageText("Votre code est  {{pin}}")
                ->setPinType(TfaPinType::NUMERIC)
                ->setPinLength(6));

        return $tfaMessageTemplate->getMessageId();
    }

    public function sendAFTemplate($appid, $phone, $messageId)
    {


            $sendCodeResponse = $this->tfaApi->sendTfaPinCodeOverSms(true,
                (new TfaStartAuthenticationRequest())
                    ->setApplicationId($appid)
                    ->setMessageId($messageId)
                    ->setFrom("Agensic")
                    ->setTo($phone));

        $isSuccessful = $sendCodeResponse->getSmsStatus() == "MESSAGE_SENT";
        $pin = $sendCodeResponse->getPinId();

        return [
            "status" => $isSuccessful,
            "pin" => $pin,
            'to' => $sendCodeResponse->getTo()
        ];
    }

    public function verifyPin($pinId, $pin)
    {
        try {
            $verifyResponse = $this->tfaApi->verifyTfaPhoneNumber($pinId, (new TfaVerifyPinRequest())->setPin($pin));
            return $verifyResponse->getVerified();
        } catch (ApiException $e) {
            return $e;
        }
    }

    public function resendOTP($pinId)
    {
        try {
            $verifyResponse = $this->tfaApi->resendTfaPinCodeOverSms($pinId, (new TfaResendPinRequest()));
            return [
                'status'=>$verifyResponse->getSmsStatus(),
                'to'=>$verifyResponse->getTo()
            ];
        } catch (ApiException $e) {
            return $e;
        }


    }
}
