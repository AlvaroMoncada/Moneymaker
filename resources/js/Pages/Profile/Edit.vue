<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});
</script>

<template>
	<Head title="Profile" />
	
	<AuthenticatedLayout>
		<template #header>
			<h1 class="text-lg font-bold">Profile</h1>
		</template>
		
		<div class="sm:px-6 md:px-0 lg:px-0 space-y-6">
				<UpdateProfileInformationForm
					:must-verify-email="mustVerifyEmail"
					:status="status"
					class="max-w-xl"
				/>
			
				<UpdatePasswordForm class="max-w-xl" />
			
				<DeleteUserForm class="max-w-xl" />
		</div>
	</AuthenticatedLayout>
</template>
