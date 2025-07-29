<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Payment;
use Laravelcm\Subscriptions\Models\Subscription;

class AbonnementController extends Controller
{
    public function listSubscription($id)
    {
        $subscriptions = Subscription::with('plan', 'customer')->where('plan_id', $id)->orderBy('id', 'desc')->get();
        return view('superAdmin.abonnements.listAbn', compact('subscriptions'));
    }

    public function paymentSubscription($user_id)
    {
        $payments = Payment::where('user_id', $user_id)->orderBy('id', 'desc')->get();
        return $payments;
    }

    public function getAuthenticatedUser()
    {
        $authenticatedUser = Customer::idCustomer();
        return Customer::findOrFail($authenticatedUser);
    }

    public function notificationSubscription()
    {
        return $this->getAuthenticatedUser()->unreadNotifications;
    }

    public function makeReadNotification($idNotif)
    {
        $user = $this->getAuthenticatedUser();
        $notification = $user->notifications()->where('id', $idNotif)->first();

        if ($notification) {
            $notification->markAsRead();
        }

        return redirect()->back();
    }
}
