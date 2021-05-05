<?php
/**
 * @author Przelewy24
 * @copyright Przelewy24
 * @license https://www.gnu.org/licenses/lgpl-3.0.en.html
 */

/**
 * Class Przelewy24RestStatusSupport
 */
class Przelewy24ClassicStatusSupport implements Przelewy24StatusSupportInterface
{
    /**
     * Get payload for log.
     *
     * @return string
     */
    public function getPayloadForLog()
    {
        return var_export($_POST, true);
    }

    /**
     * Get session id.
     *
     * @return string
     */
    public function getSessionId()
    {
        return Tools::getValue('p24_session_id');
    }

    /**
     * Get P24 order id.
     *
     * @return string
     */
    public function getP24OrderId()
    {
        return Tools::getValue('p24_order_id');
    }

    /**
     * Get P24 order id.
     *
     * @return string
     */
    public function getP24Number()
    {
        return Tools::getValue('p24_order_id');
    }

    /**
     * Possible card to save.
     *
     * @return bool
     */
    public function possibleCardToSave()
    {
        return true;
    }

    /**
     * Verify payload.
     *
     * @param string $totalAmount;
     * @param Currency $currency
     * @param string $suffix
     * @return bool
     */
    public function verify($totalAmount, $currency, $suffix)
    {
        $validation = array('p24_amount' => $totalAmount, 'p24_currency' => $currency->iso_code);

        $P24C = Przelewy24ClassInterfaceFactory::getForSuffix($suffix);

        $trnVerify = $P24C->trnVerifyEx($validation);
        PrestaShopLogger::addLog('postProcess trnVerify' . var_export($trnVerify, true), 1);

        return $trnVerify;
    }
}
