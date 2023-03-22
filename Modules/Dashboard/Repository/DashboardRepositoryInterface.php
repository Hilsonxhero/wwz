<?php

namespace Modules\Dashboard\Repository;

interface DashboardRepositoryInterface
{
    public function severalMonthsCategories();
    public function severalMonthsSales();
    public function severalMonthsOrders();
    public function monthlySales();
    public function yearlySales();
    public function dailySales();
    public function totalSales();
}
