<?php

/*
 * This file is part of the symfony framework.
 *
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

/**
 * RokCommon_Service_Reference represents a service reference.
 *
 * @package    symfony
 * @subpackage dependency_injection
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: Reference.php 48519 2012-02-03 23:18:52Z btowles $
 */
class RokCommon_Service_Reference
{
  protected
    $id = null;

  /**
   * Constructor.
   *
   * @param string $id The service identifier
   */
  public function __construct($id)
  {
    $this->id = $id;
  }

  /**
   * __toString.
   *
   * @return string The service identifier
   */
  public function __toString()
  {
    return (string) $this->id;
  }
}
