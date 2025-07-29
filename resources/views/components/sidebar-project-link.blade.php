<div
  class="hover:bg-gray-100 duration-300 cursor-pointer inline-flex items-center gap-0 w-full pl-9 hover:text-inherit focus-within:bg-cyan-200 focus-within:text-cyan-600">
  <div class="w-6 h-6 flex items-center justify-center mx-1">
    <i class="fa-regular fa-file-lines text-gray-500"></i>
  </div>
  <x-popover>
    <x-popover-button>
      @if ($role === 'etp')
        <a href="{{ route('etp.projets.show', $id) }}"
          class="h-6 text-md font-normal text-gray-500 hover:text-purple-500">Projet Réf : {{ $reference }}</a>
      @elseif ($role === 'cfp') 
        <a href="{{ route('cfp.projets.show', $id) }}"
          class="h-6 text-md font-normal text-gray-500 hover:text-purple-500">Projet Réf : {{ $reference }}</a>
      @elseif ($role === 'formateur') 
        <a href="{{ route('projetForms.detailForm', $id) }}"
          class="h-6 text-md font-normal text-gray-500 hover:text-purple-500">Projet Réf : {{ $reference }}</a>
      @elseif ($role === 'student') 
        <a href="{{ route('emps.detailEmp.index', $id) }}"
          class="h-6 text-md font-normal text-gray-500 hover:text-purple-500">Projet Réf : {{ $reference }}</a>
      @endif
    </x-popover-button>
    <x-popover-content>
      <div class="font-semibold text-base text-gray-500">Projet Réf : {{ $reference }}</div>
      <div class="flex flex-col">
        <p class="text-sm text-gray-500">Date : {{ $date }}</p>
        @foreach ($formateurs as $formateur)
          <p class="text-sm text-gray-500">Formateur : {{ $formateur->name. ' ' .$formateur->firstName }}</p>
        @endforeach
        <p class="text-sm text-gray-500">Apprenants : {{ $students }}</p>
      </div>
    </x-popover-content>
  </x-popover>
</div>
