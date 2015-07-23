<?php
namespace Topxia\WebBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Topxia\Common\Paginator;
use Topxia\Common\ArrayToolkit;
use Endroid\QrCode\QrCode;

class CommonController extends BaseController
{

    public function qrcodeAction(Request $request)
    {
        $text = $request->get('text');
        
        $qrCode = new QrCode();
        $qrCode->setText($text);
        $qrCode->setSize(250);
        $qrCode->setPadding(10);
        $img = $qrCode->get('png');

        $headers = array(
            'Content-Type' => 'image/png',
            'Content-Disposition' => 'inline; filename="qrcode.png"'
        );
        return new Response($img, 200, $headers);
    }

    public function crontabAction(Request $request)
    {
        $setting = $this->getSettingService()->get('magic', array());
        $setting = json_encode($setting);
        if (!empty($setting['web_crontab'])) {
            $this->getServiceKernel()->createService('Crontab.CrontabService')->scheduleJobs();
        }
        return $this->createJsonResponse(true);
    }

    protected function getSettingService()
    {
        return $this->getServiceKernel()->createService('System.SettingService');
    }
}