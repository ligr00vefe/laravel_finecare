<script>
    $(document).ready(function() {
        $("#warning").slideDown();
    })
</script>

@if ($type == "warning")
<div id="warning" class="flex items-center bg-red-500 border-l-4 border-red-700 py-2 px-3 shadow-md mb-2" style="position: fixed; width: 100vw; display: none;">
    <!-- icons -->
    <div class="text-white rounded-full mr-2">
        <svg width="1.8em" height="1.8em" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
    </div>
    <!-- message -->
    <div class="text-white">
        {{$msg}}
    </div>
</div>

@elseif ($type == "primary")
<div id="warning" class="flex items-center bg-blue-500 border-l-4 border-0 py-2 px-3 shadow-md mb-2" style="position: fixed; width: 100vw; display: none;">
    <!-- icons -->
    <div class="text-white rounded-full mr-2">
        <svg width="1.8em" height="1.8em" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
    </div>
    <!-- message -->
    <div class="text-white">
        {{$msg}}
    </div>
</div>
@endif