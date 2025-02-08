@extends('layouts.frontend.app')

@section('content')

<div class="main-trackParcel-page">
    <div class="trackParcel-1st-section container">
      <div class="my-5">
        <center>
          <h2 style="background-color: #F27B21; width: max-content; padding: 0px 20px !important; border-radius: 5px; color: white;">
            Track Your Parcels</h2>
          <h1 class="mb-5">Track all your goods in real time</h1>
        </center>
        <form class="container px-5" action="#">
          <div class="row">
            <div class="input-section col-12 col-lg-9">
              <div class="d-flex align-items-center justify-content-between gap-2 px-2">
                <div class="flex-grow-1">
                  <p>Parcel ID</p>
                  <input class="form-control w-100" type="text" class="form-control" placeholder="Enter your parcel ID">
                </div>
                <svg viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg" class="icon">
                  <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                  <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                  <g id="SVGRepo_iconCarrier">
                    <path d="M847.9 592H152c-4.4 0-8 3.6-8 8v60c0 4.4 3.6 8 8 8h605.2L612.9 851c-4.1 5.2-.4 13 6.3 13h72.5c4.9 0 9.5-2.2 12.6-6.1l168.8-214.1c16.5-21 1.6-51.8-25.2-51.8zM872 356H266.8l144.3-183c4.1-5.2.4-13-6.3-13h-72.5c-4.9 0-9.5 2.2-12.6 6.1L150.9 380.2c-16.5 21-1.6 51.8 25.1 51.8h696c4.4 0 8-3.6 8-8v-60c0-4.4-3.6-8-8-8z"></path>
                  </g>
                </svg>
                <div class="flex-grow-1">
                  <p>Receiver Phone Number</p>
                  <input type="text" class="form-control" placeholder="Enter your receiver phone number">
                </div>
              </div>
            </div>
            <div class="submit-section col-12 col-lg-3">
              <div class="px-2">
                <label class="d-flex justify-content-center align-items-center form-control gap-2" for="submit">
                  <img src="./favicon.ico" alt="">
                  <p>Tarack Parcel</p>
                </label>
                <input class="d-none" type="submit" name="submit" id="submit">
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

@endsection