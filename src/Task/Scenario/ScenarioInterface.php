<?php
/* (c) Anton Medvedev <anton@medv.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Deployer\Task\Scenario;

interface ScenarioInterface
{
    /**
     * Enable this scenario when the specified condition is true.
     *
     * @param bool|callable|Closure  $condition The condition upon which scenario is enabled.
     */
    public function when($condition);

    /**
     * Enable this scenario unless the specified condition is true.
     *
     * @param bool|callable|Closure  $condition The condition upon which scenario is enabled.
     */
    public function unless($condition);
}
