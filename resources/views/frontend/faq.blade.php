@extends('layouts.frontend.app')

@section('content')

<div class="main-faq-page">
	<div class="common-hero-section text-center">
		<h3 class="mb-4">
			Frequently asked <br> questions.
		</h3>
		<p class="mb-4" style="color: #6C757D;">
			Have any queries? Check out our answers to frequently asked <br> questions below, or get in touch with us to ask a representative.
		</p>
		<div class="row justify-content-center gap-2 bg-transparent mt-4">
			<a class="col-6 col-sm-3 col-lg-auto py-2 bg-white px-lg-4 shadow rounded-1 text-black fw-semibold">
				<i class="fa-solid fa-bars" style="margin-right: 7px"></i>
				Discover More
			</a>
			<a class="col-5 col-sm-3 col-lg-1 py-2 shadow rounded-1 text-white fw-semibold" style="background-color: #F27B21 !important; box-shadow: rgba(0, 0, 0, 0.15) 0px 1px 5px 0px !important;">
				<i class="fa-solid fa-phone" style="margin-right: 7px"></i>
				Contact Us
			</a>
		</div>
	</div>

	<div class="faq-2nd-section container">
		<div class="accordion" id="accordionExample">
			<div class="accordion-item">
				<h2 class="accordion-header" id="headingOne">
					<button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne"
						aria-expanded="true" aria-controls="collapseOne">
						Can I have multiple Pickup Locations?
					</button>
				</h2>
				<div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
					data-bs-parent="#accordionExample">
					<div class="accordion-body">
						Yes, you can set as many pickup locations as you need.
					</div>
				</div>
			</div>
			<div class="accordion-item">
				<h2 class="accordion-header" id="headingTwo">
					<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
						data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
						How do I open an account with you?
					</button>
				</h2>
				<div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
					data-bs-parent="#accordionExample">
					<div class="accordion-body">
						Please click on Registration Or fill out the contact us here and we will be in touch with you.
					</div>
				</div>
			</div>
			<div class="accordion-item">
				<h2 class="accordion-header" id="headingThree">
					<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
						data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
						At What Time Will the Rider Pick Up My Parcels?
					</button>
				</h2>
				<div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree"
					data-bs-parent="#accordionExample">
					<div class="accordion-body">
						You can choose the pick-up timings based on the driver availability and schedule in your area.
					</div>
				</div>
			</div>
			<div class="accordion-item">
				<h2 class="accordion-header" id="headingThree">
					<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
						data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
						How Can I Contact Your Customer Service Team?
					</button>
				</h2>
				<div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree"
					data-bs-parent="#accordionExample">
					<div class="accordion-body">
						You can call to Parceldex Customer care +8801601057407 or or just email on support@parceldex.com to get easy access to detailed advice and support, powered by real people who are always happy to help.
					</div>
				</div>
			</div>
			<div class="accordion-item">
				<h2 class="accordion-header" id="headingThree">
					<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
						data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
						Do you offer COD (Cash on Delivery) services?
					</button>
				</h2>
				<div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree"
					data-bs-parent="#accordionExample">
					<div class="accordion-body">
						Yes, We offer COD (Cash on Delivery) Service in All over Bangladesh.
					</div>
				</div>
			</div>
			<div class="accordion-item">
				<h2 class="accordion-header" id="headingThree">
					<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
						data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
						Can you do a late pickup?
					</button>
				</h2>
				<div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree"
					data-bs-parent="#accordionExample">
					<div class="accordion-body">
						Yes, depending on the business the client provides (monthly order volume), we can do late pickups.
					</div>
				</div>
			</div>
			<div class="accordion-item">
				<h2 class="accordion-header" id="headingThree">
					<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
						data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
						What are the SLAs from pick-up to delivery?

					</button>
				</h2>
				<div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree"
					data-bs-parent="#accordionExample">
					<div class="accordion-body">
						Parceldex Ltd deliver Same city within 24 hours, Sub-city within 48 hours, Outside city within 72 hours (The timeline may vary based on the remote area, Natural calamities, Unavoidable political issues)
					</div>
				</div>
			</div>
			<div class="accordion-item">
				<h2 class="accordion-header" id="headingThree">
					<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
						data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
						How Can I Track My Orders?
					</button>
				</h2>
				<div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree"
					data-bs-parent="#accordionExample">
					<div class="accordion-body">
						In the Merchant Portal and enter your merchant login credentials and you’ll see the status of all your orders. Your customers can also track their orders at https://parceldex.com.bd/orderTracking
					</div>
				</div>
			</div>
			<div class="accordion-item">
				<h2 class="accordion-header" id="headingThree">
					<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
						data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
						What types of vehicles do you have?
					</button>
				</h2>
				<div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree"
					data-bs-parent="#accordionExample">
					<div class="accordion-body">
						Our fleet includes Cycle, bikes and vans.
					</div>
				</div>
			</div>
			<div class="accordion-item">
				<h2 class="accordion-header" id="headingThree">
					<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
						data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
						What’s the Best Way to Package My Shipment?
					</button>
				</h2>
				<div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree"
					data-bs-parent="#accordionExample">
					<div class="accordion-body">
						The right packaging depends on what item is being delivery. Please contact your KAM or support team to get the extensive guide on how to package your order. For Dropship items, you must adhere to these guidelines before you hand over your parcel to us.
					</div>
				</div>
			</div>
			<div class="accordion-item">
				<h2 class="accordion-header" id="headingThree">
					<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
						data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
						How Can I Claim Compensation for a Lost or Damaged Item?
					</button>
				</h2>
				<div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree"
					data-bs-parent="#accordionExample">
					<div class="accordion-body">
						The client must inform about any lost or damaged item within X hours of the delivery. There shall be an internal audit and investigation to prepare a root cause analysis report. If the damage was done due to an operational error, a credit note to the value of the shipment shall be issued. However, if the package did not meet the recommended packaging guidelines, the claim shall be declined. We are always available to answer any other queries you may have. If there’s anything that we haven’t covered here, please send an email to your assigned account manager or just email on support@parceldex.com
					</div>
				</div>
			</div>
			<div class="accordion-item">
				<h2 class="accordion-header" id="headingThree">
					<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
						data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
						What are the standard Dimensions & Size?
					</button>
				</h2>
				<div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree"
					data-bs-parent="#accordionExample">
					<div class="accordion-body">
						We accept 78cm x 78cm & weight upto 10kg
					</div>
				</div>
			</div>
			<div class="accordion-item">
				<h2 class="accordion-header" id="headingThree">
					<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
						data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
						What happens to my parcel if I cancel?
					</button>
				</h2>
				<div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree"
					data-bs-parent="#accordionExample">
					<div class="accordion-body">
						If your parcel is cancelled, it will be informed to you and will be returned asap with next pickup or maximum in 1-3 days.
					</div>
				</div>
			</div>
			<div class="accordion-item">
				<h2 class="accordion-header" id="headingThree">
					<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
						data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
						What services do you provide?
					</button>
				</h2>
				<div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree"
					data-bs-parent="#accordionExample">
					<div class="accordion-body">
						We provide pickup, Delivery, COD (Cash on Delivery), RTOs (Returns), Cash Deposit, Packaging, Warehousing and related services for delivery
					</div>
				</div>
			</div>
			<div class="accordion-item">
				<h2 class="accordion-header" id="headingThree">
					<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
						data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
						Do you deliver all items?
					</button>
				</h2>
				<div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree"
					data-bs-parent="#accordionExample">
					<div class="accordion-body">
						Except the below item we deliver all. 1. Ammunition/ Firearms 2. Animals 3. Antiques or Fine works of arts 4. Alcoholic beverage 5. Batteries Expensive electronics items (Mobile, camera, Personal computer, Digital Diaries, etc.) 6. 7. Fragile items (if not packed well) 8. Human remains or Ashes 9. Ivory 10. Industrial diamonds 11. Liquids (if not packed well) 12. Medicines and medical samples (including blood, urine, tissue, etc.) 13. Narcotics 14. Pornography 15. Perishable items 16. Pharmaceutical products 17. Plant and plant products (Cotton, Seeds, tobacco)
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection
