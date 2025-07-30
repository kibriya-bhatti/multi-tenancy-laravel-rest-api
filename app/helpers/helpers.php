<?php
if (!function_exists('tenant')) {
    function tenant() {
        // Return an object with a tenant() method, or however you manage tenants
        return auth()->user()->tenant ?? null; // Or your own logic
    }
}
