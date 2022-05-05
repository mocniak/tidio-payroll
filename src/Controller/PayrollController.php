<?php

namespace App\Controller;

use App\View\PayrollFilter;
use App\View\PayrollFilterType;
use App\View\PayrollOrder;
use App\View\PayrollOrderType;
use App\View\Payrolls;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="/api/payroll", name="payroll")
 */
class PayrollController
{
    private Payrolls $payrolls;

    public function __construct(Payrolls $payrolls)
    {
        $this->payrolls = $payrolls;
    }

    public function __invoke(Request $request): Response
    {
        $filter = null;
        if (null !== $request->query->get('filter')) {
            $filterParams = explode(':', $request->query->get('filter'));
            $filter = new PayrollFilter(PayrollFilterType::from($filterParams[0]), $filterParams[1]);
        }
        $order = null;
        if (null !== $request->query->get('order')) {
            $orderParams = explode(':', $request->query->get('order'));
            $order = new PayrollOrder(PayrollOrderType::from($orderParams[0]), $orderParams[1] === 'ASC');
        }

        return new JsonResponse($this->payrolls->listPayrolls($filter, $order));
    }
}
