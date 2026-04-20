@extends('layouts.web.master')
@section('title', 'Referrals')
@section('content')

<!-- Banner Section -->
<section class="inner-banner about-banner">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1 class="hd-lg">Referrals</h1>
            </div>
        </div>
    </div>
</section>

<!-- How Referrals Work Section -->
<section class="referrals-sec sec-dark-bg sec-gap-y">
    <div class="container">
        <div class="row row-gap-40 mb-60">
            <div class="col-12 text-center">
                <h2 class="hd-lg mb-20">How Referrals Work</h2>
                <p class="para">
                    League Of Contractors digitizes the traditional "word-of-mouth" contractor network, transforming it into a structured online ecosystem where verified members can collaborate, earn through referrals, and access strategic business resources.
                </p>
            </div>
        </div>

        <!-- Referral System Features -->
        <div class="row row-gap-40">
            <div class="col-lg-6">
                <h2 class="hd-lg hd-md mb-40">Referral Tracking System</h2>
                <ul class="secondary-list">
                    <li>
                        <h4 class="secondary-list-title">Track Referrals Between Members</h4>
                        <p class="para">The system automatically tracks all referrals between verified League members. You can send referrals to other contractors and receive referrals from members in your network.</p>
                    </li>
                    <li>
                        <h4 class="secondary-list-title">Automated Rebate Calculation</h4>
                        <p class="para">Rebates and referral credits are calculated automatically by the system. The platform handles all calculations, ensuring accurate and timely credit distribution.</p>
                    </li>
                    <li>
                        <h4 class="secondary-list-title">Dashboard Tracking</h4>
                        <p class="para">Monitor all your referrals, rebates, and earnings through your member dashboard. Track your referral activity, view detailed reports, and manage your referral network in one place.</p>
                    </li>
                </ul>
            </div>
            <div class="col-lg-6">
                <h2 class="hd-lg hd-md mb-40">Referral Features</h2>
                <ul class="secondary-list">
                    <li>
                        <h4 class="secondary-list-title">Send & Receive Referrals</h4>
                        <p class="para">As a verified contractor, you can send referrals to other League members and receive referrals from your network. The system tracks all referral activity automatically.</p>
                    </li>
                    <li>
                        <h4 class="secondary-list-title">County-Based Exclusivity</h4>
                        <p class="para">Participate in county-based exclusivity to enhance visibility and credibility. Limited trades per county maintain exclusivity and create strategic referral opportunities within your area.</p>
                    </li>
                    <li>
                        <h4 class="secondary-list-title">Verified Network</h4>
                        <p class="para">All referrals happen within a verified network of licensed contractors. Every member is pre-screened and approved, ensuring quality and trust in every referral partnership.</p>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

@endsection