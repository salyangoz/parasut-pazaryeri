<?php

namespace salyangoz\pazaryeriparasut;
use Carbon\Carbon;
use salyangoz\pazaryeriparasut\Marketplace\Hepsiburada;
use salyangoz\pazaryeriparasut\Services\EInvoice;
use Exception;
use Illuminate\Support\Facades\Log;
use salyangoz\pazaryeriparasut\Services\Push;

class Client implements PazaryeriParasut
{

    private $config;

    /** 
     * Constructor.
     *
     * @param  array  $config
     * @return void
     */
    public function __construct(array $config)
    {
        $this->config   =   $config;
    }

    public function transfer()
    {
        $einvoice   =   new EInvoice($this->config);
        $einvoice->transfer();
    }

    public function transferEInvoices()
    {
        $parasutAdapter =   new ParasutAdapter($this->config,"GG");
        $parasutAdapter->transferEInvoices();
    }

    public function pull()
    {

        try
		{
			if($this->config['gittigidiyor_api_key']) {
				$gittigidiyorMarket = new Marketplace\Gittigidiyor($this->config);
				$gittigidiyorMarket->pull();
			}
		}
		catch(Exception $e)
		{
			Log::error($e->getMessage());
		}
		
		try
		{
			if($this->config['n11_app_key']) {
				$n11    =   new Marketplace\N11($this->config);
				$n11->pull();
			}
		}
		catch(Exception $e)
		{
			Log::error($e->getMessage());
		}

		try
        {
			if($this->config['hepsiburada_username']) {
				
				$hepsiburada    =   new Hepsiburada($this->config);
				$hepsiburada->pull();
			
			}
        }
        catch (Exception $e)
        {
            Log::error($e->getMessage());
        }

    }

    public function push()
    {
        $push = new Push($this->config);
        $push->start();
    }

    public function einvoiceRequest()
    {
        $einvoice   =   new EInvoice($this->config);
        $einvoice->request();
    }

}
