<?php
/* (c) Anton Medvedev <anton@medv.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Deployer\Task\Scenario;

class Scenario implements ScenarioInterface
{
    /**
     * @var string
     */
    private $taskName;

    /**
     * @var Scenario[]
     */
    private $after = [];

    /**
     * @var Scenario[]
     */
    private $before = [];

    /**
     * @var bool|callable|Closure
     */
    private $condition = true;

    /**
     * @var string;
     */
    private $operator = '==';

    /**
     * @param string $taskName
     */
    public function __construct($taskName)
    {
        $this->taskName = $taskName;
    }

    /**
     * @return array
     */
    public function getTasks()
    {
        $tasks = [];
        if ($this->isEnabled()) {
            $tasks = array_merge(
                $this->getBefore(),
                [$this->taskName],
                $this->getAfter()
            );
        }
        return $tasks;
    }

    /**
     * @param Scenario $scenario
     */
    public function addBefore(Scenario $scenario)
    {
        array_unshift($this->before, $scenario);
    }

    /**
     * @param Scenario $scenario
     */
    public function addAfter(Scenario $scenario)
    {
        array_push($this->after, $scenario);
    }

    /**
     * Get before tasks names.
     * @return string[]
     */
    protected function getBefore()
    {
        $tasks = [];
        foreach ($this->before as $scenario) {
            $tasks = array_merge($tasks, $scenario->getTasks());
        }
        return $tasks;
    }

    /**
     * Get after tasks names.
     * @return string[]
     */
    protected function getAfter()
    {
        $tasks = [];
        foreach ($this->after as $scenario) {
            $tasks = array_merge($tasks, $scenario->getTasks());
        }
        return $tasks;
    }

    /**
     * Determine whether this scenario is enabled.
     *
     * @return bool
     */
    protected function isEnabled()
    {
        $enabled = $this->condition;
        if (!is_bool($this->condition)) {
            if ($this->condition instanceof \Closure) {
                $condition = $this->condition;
                $enabled = (bool) $condition();
            } elseif (is_callable($this->condition)) {
                $enabled = (bool) call_user_func($condition);
            } else {
                // Invalid condition
                throw new \InvalidArgumentException(
                    'The condition should be a boolean, callable or Closure value.'
                );
            }
        }
        return $this->operator === "==" ? $enabled : ! $enabled;
    }

    /**
     * Enable this scenario when the specified condition is true.
     *
     * @param bool|callable|Closure  $condition The condition upon which scenario is enabled.
     */
    public function when($condition)
    {
        $this->condition = $condition;
        $this->operator = '==';
    }

    /**
     * Enable this scenario unless the specified condition is true.
     *
     * @param bool|callable|Closure  $condition The condition upon which scenario is enabled.
     */
    public function unless($condition)
    {
        $this->condition = $condition;
        $this->operator = '!=';
    }
}
