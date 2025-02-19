
@section('seo-meta')
    @php
        $seoMeta = App\Models\SeoMeta::where('page_name', 'ReturnRefundPolicy')->first();
    @endphp

    @if ($seoMeta)
        <title>{{ $seoMeta->og_title }}</title>

        <meta name="description" content="{{ $seoMeta->og_description }}">
        <meta name="keywords" content="{{ $seoMeta->meta_keywords }}">

        <meta property="og:title" content="{{ $seoMeta->og_title }}">
        <meta property="og:description" content="{{ $seoMeta->og_description }}">
        <meta property="og:image" content="{{ asset('storage/images/' . $seoMeta->og_image) }}">
        <meta property="og:url" content="{{ url()->current() }}">

        <meta name="twitter:title" content="{{ $seoMeta->og_title }}">
        <meta name="twitter:description" content="{{ $seoMeta->og_description }}">
        <meta name="twitter:image" content="{{ asset('storage/images/' . $seoMeta->og_image) }}">

        <link rel="canonical" href="{{ url()->current() }}">

        <!-- JSON-LD Schema Markup for Services Page -->
        <script type="application/ld+json">
     {
       "@context": "https://schema.org",
       "@type": "Service",
       "name": "Courier Services",
       "url": "{{url()->current()}}",
       "provider": {
         "@type": "CourierService",
         "name": "Parceldex Courier",
         "url": "https://parceldex.com"
       },
       "serviceType": "Express Delivery, Same-Day Delivery, Next-Day Delivery, Cash on Delivery, eCommerce Logistics",
       "areaServed": {
         "@type": "Country",
         "name": "Bangladesh"
       },
       "availableChannel": {
         "@type": "ServiceChannel",
         "serviceUrl": "{{url()->current()}}",
         "availableLanguage": ["English", "Bengali"]
       },
       "offers": {
         "@type": "Offer",
         "priceCurrency": "BDT",
         "price": "Varies",
         "url": "{{url()->current()}}"
       },
       "contactPoint": {
         "@type": "ContactPoint",
         "telephone": "+8809642727727",
         "contactType": "customer service"
       }
     }
     </script>
    @endif
@endsection

@extends('layouts.frontend.app')

@section('content')
    <div class="main-returnRefundPolicy-page">
        <div class="common-hero-section text-center">
            <div class="easy-translate">
                <label class="switch">
                    <input type="checkbox" id="toggleSwitch">
                    <span class="slider"></span>
                </label>
            </div>
            <h3 class="mb-4">
                Return & Refund Policy
            </h3>
            <p class="mb-4" style="color: #6C757D;">
                Find out more about our policies and other regulations to gain<br> information about how our services operate.
            </p>
            <div class="row justify-content-center gap-2 bg-transparent mt-4">
                <a class="col-5 col-sm-3 col-lg-1 py-2 shadow rounded-1 text-white fw-semibold" style="background-color: #F27B21 !important; box-shadow: rgba(0, 0, 0, 0.15) 0px 1px 5px 0px !important;">
                    <i class="fa-solid fa-phone" style="margin-right: 7px"></i>
                    Contact Us
                </a>
            </div>
        </div>
    </div>

    <center>
        <div class="termsAndConditions-2nd-section container row justify-content-center min-vw-100 my-5">
        <div class="col-11 col-md-10 col-lg-6">
            <h5 class="my-5 fs-2 fw-semibold text-center" style="font-family: system-ui; letter-spacing: -1px;">Return and Refund Policy</h5>
            <div id="eng-text" style="display: none">
                <strong class="mb-5 fs-4 text-left w-100" style="color: #737373;">Return Policy:</strong>
                <p class="my-3" style="font-size: 17px; font-weight: 700; line-height: 30px;">
                    1. If the customer is unreachable at the first attempt, Paperfly shall make another two attempts on two different days before confirming a return.
                    <br>
                    <br>
                    2. If the customer is unavailable after three attempts or agreed to return the package(s) for any reason, Paperfly shall return the product to the Merchant.
                    <br>
                    <br>
                    3. Paperfly will confirm through OTP verification whenever any product refusal is made by the customer.
                    <br>
                    <br>
                    4. Paperfly will maintain undelivered package return back to Merchant within 7-10 days for same district shipment and within 10-15 days for other district shipments.
                    <br>
                    <br>
                    5. Paperfly shall not be responsible to take a product as a return when a customer opened the seal of the product box and then wants to return it.
                    <br>
                    <br>
                    6. In case of any undelivered package, when returned to the Client, the Client shall consider receiving the package while the product(s) are returned in good condition; irrespective of packet damage, the packet is torn, packet quality degrade, or invoice missing from the product, that might happen due to extensive movement of product for the delivery attempt and return process.
                    <br>
                    <br>
                    7. In case of any consignment is lost during transit, Paperfly will be liable to compensate based on mutual discussion up to 50% of Production or Manufactural cost, but not exceeding Tk. 10,000.
                    <br>
                    <br>
                    8. In case of repairable/partial damage, Paperfly will compensate only for the repair cost of such damage. However, in case of irreparable/full damage, the compensation will be determined on mutual discussion up to 50% of Production or Manufactural cost, but not exceeding Tk. 10,000.
                    <br>
                    <br>
                    9. Furthermore, on request of the Merchant, the Paperfly may issue a loss/damage acknowledgement receipt with the sole purpose of enabling the Merchant to lodge an insurance claim (if any).
                    <br>
                    <br>
                    10. Any dispute claim from the Merchant shall be raised within 15 days from the date of pickup. Any claim landed to Paperfly after a given duration will not be considered.
                    <br>
                    <br>
                    11. No Damage and/or lost claims by the Client shall be entertained for improper packaging or packaging flaw.
                    <br>
                    <br>
                    12. Any parcel containing the following items shall not be under consideration of any compensation; 1) Fragile Item, 2) Liquid Item, 3) Sensitive Cosmetic Item, 4) Perishable Items.
                    <br>
                    <br>
                    13. If any document is lost or damaged during transit, Paperfly shall compensate the re-printing or re-preparation cost based on mutual discussion.
                    <br>
                    <br>
                    14. For Exchange service, Paperfly will not accept any dispute claim on product condition as Paperfly will receive the open box / open parcel from customer end. Dispute claim may only be applicable if the product is lost by Paperfly.
                </p>
            </div>
            <div id="bd-text" style="display: none">
                <strong class="mb-5 fs-4 text-left w-100" style="color: #737373;">শর্তাবলী:</strong>
                <p class="my-3" style="font-size: 17px; font-weight: 500; line-height: 30px;">

                    পেপারফ্লাই প্রাইভেট লিমিটেড বাংলাদেশের বৃহত্তম ও দ্রুত পরিসরে বেড়ে ওঠা লজিস্টিক সেবা প্রদানকারী প্ল্যাটফর্ম। আমরা বৈধ কুরিয়ার এবং ট্রেড লাইসেন্স এর সাথে ২০১৬ থেকে সেবা প্রদান করে আসছি। মার্চেন্টদের সম্পূর্ণ এবং কাস্টমাইজড লজিস্টিক সহায়তা প্রদান করাই আমাদের লক্ষ্য। আমাদের সেবা সমূহের মধ্যে রয়েছে মার্চেন্টদের নিকট হতে ডোরস্টেপ পিক-আপ, ডোরস্টেপ ডেলিভারি, ওয়্যারহাউজিং, প্যাকেজিং, ক্যাশ-অন-ডেলিভারি, রিটার্ন ম্যানেজমেন্ট ইত্যাদি ৬৪টি জেলা জুড়ে। আমরা বর্তমানে দেশের বৃহত্তম ই-কমার্স প্রতিষ্ঠানগুলোকে এই পরিষেবাগুলো প্রদান করছি।
                    <br>
                    <br>
                    <strong>মার্চেন্টদের সর্বোচ্চ এবং মানসম্মত সেবা প্রদান করাই আমাদের লক্ষ্য। এই সেবা প্রাপ্তি সহজ ও নিশ্চিত করতে সম্মানিত মার্চেন্টদের সম্মতি হওয়া শর্তাবলি উপস্থাপন করছি: -</strong>
                    <br>
                    <br>
                    <br>
                    <strong>অর্ডার প্রসেসিং এবং ডেলিভারি পলিসি :-</strong>
                    <br>
                    ১. মার্চেন্টকে পেপারফ্লাই প্যানেলে যথাযথ তথ্য প্রদানের মাধ্যমে তার অর্ডার আপলোড করবে। যেমন প্রোডাক্টের ধরণ, অর্ডার নম্বর, এবং কাস্টমারের যাবতীয় তথ্য দিয়ে পরবর্তী ধাপের জন্য আবেদন করতে হবে।
                    <br>
                    <br>
                    ২. পেপারফ্লাই-এ প্রোডাক্ট দেওয়ার পূর্বেই পণ্য সমূহকে অবশ্যই সুরক্ষিতভাবে প্যাক (বাবল-র‍্যাপ অথবা বক্স প্যাকেজিং ইত্যাদি) করে প্রস্তুত রাখতে হবে। যথাযথ প্যাকেজিং এর কারনে কোনো প্রোডাক্ট নষ্ট হয়ে গেলে পেপারফ্লাই তার ক্ষতিপূরণ প্রদান করবে না। 
                    <br>
                    <br>
                    ৩. মার্চেন্টদেরকে প্রতিদিন দুপুর ৩টার মধ্যে পেপারফ্লাই উইংস প্যানেলে অর্ডার প্লেস করতে হবে। এই অর্ডারগুলো পেপারফ্লাই সেদিনের অর্ডার হিসেবে গ্রহণ করবে।
                    <br>
                    <br>
                    ৪. মার্চেন্টকে প্রতিটি অর্ডার প্যাকেজ এর সাথে একটি ইনভয়েস ইস্যু করতে হবে।
                    <br>
                    <br>
                    ৫. পেপারফ্লাই ডেলিভারি (এসএলএ)- একই জেলার ভেতর: ২৪ ঘণ্টা, একই বিভাগের ভেতর: ২৪-৪৮ ঘণ্টা, অন্যান্য জেলায়: ২৪-৭২ ঘণ্টা। তবে প্রত্যন্ত এলাকায় ডেলিভারিতে অতিরিক্ত সময় প্রয়োজন হতে পারে।
                    <br>
                    <br>
                    ৬. পেপারফ্লাই পুরো পেমেন্ট পাওয়ার পর পণ্য গ্রাহকের কাছে হস্তান্তর করবে এবং তারপর ডেলিভারি কার্যক্রম সম্পন্ন করবে। নন-সিওডি অর্ডারের ক্ষেত্রে গ্রাহকের কাছে পণ্য প্রদানের পূর্বে গ্রাহকের কাছ থেকে ওটিপি যাচাই করা নেওয়া হবে।
                    <br>
                    <br>
                    ৭. কোনো ডোরস্টেপ ডেলিভারির লোকেশন যদি পেপারফ্লাই কভারেজ এরিয়ার বাইরে হয়, তাহলে কাস্টমারকে নিকটবর্তী পেপারফ্লাই কাউন্টার থেকে পার্সেলটি সংগ্রহ করতে হবে।
                    <br>
                    <br>
                    ৮. পেপারফ্লাই ডেলিভারি প্রতিনিধি মার্চেন্টের হয়ে কাস্টমারকে সময়মত প্রোডাক্ট ডেলিভারি করবে (মার্চেন্টের দেওয়া কাস্টমারের নম্বর এবং ঠিকানা সঠিক প্রদান করা সাপেক্ষে)
                    <br>
                    <br>
                    <br>
                    <strong>রিটার্ন পলিসি-</strong>
                    <br>
                    <br>
                    ৯. ডেলিভারির নির্ধারিত সময়ে গ্রাহক অনুপস্থিত থাকলে পেপারফ্লাই পণ্য রিটার্নের পূর্বে আরও দুইবার (পরবর্তী দুইদিনে) গ্রাহকের ঠিকানায় পণ্য ডেলিভারি করার চেষ্টা করবে। 
                    <br>
                    <br>
                    ১০. পর পর তিনবার অ্যাটেম্পটেড নেওয়ার পর গ্রাহক একেবারেই অনুপস্থিত থাকলে বা অন্য কোনো কারণে প্যাকেজ রিটার্ন হলে মার্চেন্টের সম্মতিক্রমে পেপারফ্লাই মার্চেন্টকে তার প্যাকেজ ফিরিয়ে দিবে।
                    <br>
                    <br>
                    ১১. কাস্টমার কোনো প্রোডাক্ট রিটার্ন করলে ওটিপি ভ্যারিফিকেশনের মাধ্যমে পেপারফ্লাই সেটি নিশ্চত করবে। 
                    <br>
                    <br>
                    ১২. প্রোডাক্ট রিটার্ন এসএলএ - একই জেলার অর্ডার: ৭- ১০ দিনের মধ্যে, একই বিভাগের অর্ডার: ১০ দিনের মধ্যে, অন্যান্য জেলায় অর্ডার: ১৫ দিনের মধ্যে।   
                    <br>
                    <br>
                    ১৩. যদি কোনো কাস্টমার বক্সের সিল খোলেন তারপর এটি ফেরত দিতে চান সেক্ষেত্রে পেপারফ্লাই প্রতিনিধি ওই প্রোডাক্টের কোনোও দায়িত্ব গ্রহন করবে না।
                    <br>
                    <br>
                    ১৪. রিটার্ন প্রোডাক্ট কাস্টমারের কাছে ফেরত পাঠানো অবস্থায় যদি মূল পণ্য অক্ষত রেখে প্যাকেট বা ইনভয়েসের কোনো সামান্য ক্ষতি হয় (অতিরিক্ত রিটার্ন মুভমেন্টের কারণে) তাহলে মার্চন্টকে পণ্য রিসিভ করা নিশ্চিত করতে হবে।
                </p>
            </div>
        </div>
    </div>
    </center>

</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggleSwitch = document.getElementById('toggleSwitch');
        const engText = document.getElementById('eng-text');
        const bdText = document.getElementById('bd-text');

        function updateUI(state) {
            const isOn = state === 'ON';
            toggleSwitch.checked = isOn;
            bdText.style.display = isOn ? 'block' : 'none';
            engText.style.display = isOn ? 'none' : 'block';
        }

        toggleSwitch.addEventListener('change', () => {
            const newState = toggleSwitch.checked ? 'ON' : 'OFF';
            localStorage.setItem('toggleState', newState);
            updateUI(newState);
        });

        const savedState = localStorage.getItem('toggleState') || 'OFF';
        updateUI(savedState);
    });
</script>
@endsection