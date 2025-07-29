@extends('layouts.masterAdmin')
@section('content')
  <div class="flex flex-col justify-center items-start">
    <div class="text-2xl font-semibold flex flex-col justify-center items-start text-gray-500">
      Assiduités
      <div
        class="flex flex-col gap-2 justify-center items-center border-t-[1px] border-l-[1px] border-gray-300 rounded-tl-md mt-2 px-4">
        <ul>
          <li>Fiche de présences</li>
          <li>Présents</li>
          <li>Absents</li>
        </ul>
      </div>
    </div>
  </div>
@endsection
