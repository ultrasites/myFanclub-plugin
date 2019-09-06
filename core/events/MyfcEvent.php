<?php
namespace myfanclub\core\events;

/**
 * Event Interface
 *
 * Every core event must implement this interface!
 *
 * @package  core/events
 * @author   Ultra Sites Medienagentur <info@ultra-sites.de>
 * @version  beta
 * @access   public
 * @see      https://myfanclub.ultra-sites.de
 */
interface MyfcEvent
{
    public static function className();
    public function handle($params = []);
}
