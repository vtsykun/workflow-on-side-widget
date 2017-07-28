<?php

namespace Oro\Bundle\TaskBundle\Provider;

use Doctrine\Common\Collections\Collection;
use Oro\Bundle\ActionBundle\Button\ButtonContext;
use Oro\Bundle\ActionBundle\Button\ButtonInterface;
use Oro\Bundle\ActionBundle\Button\ButtonSearchContext;
use Oro\Bundle\TaskBundle\Button\SideTransitionButton;
use Oro\Bundle\WorkflowBundle\Extension\TransitionButtonProviderExtension;
use Oro\Bundle\WorkflowBundle\Model\Transition;
use Oro\Bundle\WorkflowBundle\Model\Workflow;

class SideTransitionButtonProvider extends TransitionButtonProviderExtension
{
    /** @var ButtonContext */
    protected $baseButtonContext;

    /**
     * {@inheritdoc}
     */
    public function supports(ButtonInterface $button)
    {
        return $button instanceof SideTransitionButton && !$button->getTransition()->isStart();
    }

    /**
     * {@inheritdoc}
     * @param SideTransitionButton $button
     */
    public function isAvailable(
        ButtonInterface $button,
        ButtonSearchContext $buttonSearchContext,
        Collection $errors = null
    ) {
        if (!$this->supports($button)) {
            throw $this->createUnsupportedButtonException($button);
        }

        $entityId = $buttonSearchContext->getEntityId();
        if (is_array($entityId)) {
            $entityId = reset($entityId);
        }

        $workflowItem = $button->getWorkflow()->getWorkflowItemByEntityId($entityId);

        if ($workflowItem === null) {
            return false;
        }

        $transition = $button->getTransition();
        $workflow = $button->getWorkflow();
        try {
            $isAvailable = $workflow->isTransitionAllowed($workflowItem, $transition, $errors);
        } catch (\Exception $e) {
            $isAvailable = false;
            if (null !== $errors) {
                $errors->add(['message' => $e->getMessage(), 'parameters' => []]);
            }
        }

        return $isAvailable;
    }

    /**
     * {@inheritdoc}
     */
    public function find(ButtonSearchContext $buttonSearchContext)
    {
        $buttons = [];
        $groups = (array) $buttonSearchContext->getGroup();

        if (!in_array('side-widget', $groups, true)) {
            return $buttons;
        }

        foreach ($this->getActiveWorkflows() as $workflow) {
            $transitions = $this->getTransitions($workflow, $buttonSearchContext);

            foreach ($transitions as $transition) {
                $buttonContext = $this->generateButtonContext($transition, $buttonSearchContext);
                $buttons[] = $this->createTransitionButton($transition, $workflow, $buttonContext);
            }
        }

        $this->baseButtonContext = null;

        return $buttons;
    }

    /**
     * @param Transition $transition
     * @param ButtonSearchContext $searchContext
     *
     * @return ButtonContext
     */
    protected function generateButtonContext(Transition $transition, ButtonSearchContext $searchContext)
    {
        if (!$this->baseButtonContext) {
            $this->baseButtonContext = new ButtonContext();
            $this->baseButtonContext->setDatagridName($searchContext->getDatagrid())
                ->setEntity($searchContext->getEntityClass(), $searchContext->getEntityId())
                ->setRouteName($searchContext->getRouteName())
                ->setGroup($searchContext->getGroup())
                ->setExecutionRoute($this->routeProvider->getExecutionRoute());
        }

        $context = clone $this->baseButtonContext;
        $context->setUnavailableHidden($transition->isUnavailableHidden());

        if ($transition->hasForm()) {
            $context->setFormDialogRoute($this->routeProvider->getFormDialogRoute());
            $context->setFormPageRoute($this->routeProvider->getFormPageRoute());
        }

        return $context;
    }

    /**
     * {@inheritdoc}
     */
    protected function getTransitions(Workflow $workflow, ButtonSearchContext $searchContext)
    {
        if ($workflow->getDefinition()->getRelatedEntity() === $searchContext->getEntityClass()) {
            $transitions = $workflow->getTransitionManager()->getTransitions()->toArray();

            return array_filter($transitions, function (Transition $transition) {
                return !$transition->isStart();
            });
        }

        return [];
    }

    /**
     * {@inheritdoc}
     */
    protected function createTransitionButton(
        Transition $transition,
        Workflow $workflow,
        ButtonContext $buttonContext
    ) {
        return new SideTransitionButton($transition, $workflow, $buttonContext);
    }
}
