<?php
/* (c) Anton Medvedev <anton@medv.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Deployer\Task\Scenario;

use Deployer\Task\Scenario\Scenario;

class ConditionalScenario extends Scenario
{
    /**
     * Holds the wrapped scenario.
     * @var Scenario
     */
    private $scenario;

    /**
     * Holds the condition that determines whether this scenario's tasks are executed.
     * @var bool|callable|Closure
     */
    protected $condition;

    /**
     * @param Scenario $scenario
     * @param bool|callable|Closure  $condition The condition upon which scenario's tasks are executed.
     */
    public function __construct(Scenario $scenario, $condition)
    {
        parent::__construct(null);
        $this->scenario = $scenario;
        $this->condition = $condition;
    }

    /**
     * {@inheritdoc}
     */
    public function getTasks()
    {
        if ($this->conditionSatisfied()) {
            return $this->scenario->getTasks();
        }
        return [];
    }

    /**
     * Determine whether this scenario's condition is satisfied.
     *
     * @return bool
     */
    protected function conditionSatisfied() {
        if (!is_bool($this->condition)) {
            if ($this->condition instanceof \Closure) {
                $this->condition = (bool) $this->condition();
            } elseif (is_callable($this->condition)) {
                $this->condition = (bool) call_user_func($condition);
            } else {
                $this->condition = false;
            }
        }
        return $this->condition;
    }
}
