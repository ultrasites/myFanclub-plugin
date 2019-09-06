<?php
namespace myfanclub\core;

/**
 * Module Factory Interface
 *
 * It must be implements by every module factory
 *
 * @package  core/events
 * @author   Ultra Sites Medienagentur <info@ultra-sites.de>
 * @version  beta
 * @access   public
 * @see      https://myfanclub.ultra-sites.de
 */
interface MyfcModuleFactory
{
    public function myfcCapabilitiesAdministrator();
    public function myfcCapabilitiesSecretary();
    public function myfcCapabilitiesCashier();
    public function myfcCapabilitiesMember();
    public function myfcCapabilitiesChairman();
    public function myfcSubMenueEntry();
    public function myfcEventRegister();
}
