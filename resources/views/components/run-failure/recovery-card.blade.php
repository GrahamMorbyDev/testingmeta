<!--
  Blade partial to include the failure recovery panel in run detail pages.
  Usage: @include('components.run-failure.recovery-card', ['run' => $run])
-->
<div class="mt-6">
    @livewire('run-failure-panel', ['run' => $run])
</div>
