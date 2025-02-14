<script
  setup
  lang="ts"
>
  import { ref } from 'vue';

  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
  import { Head, Link } from '@inertiajs/vue3';
  import { usePage, useForm } from '@inertiajs/vue3';

  import InputError from '@/Components/InputError.vue';
  import InputLabel from '@/Components/InputLabel.vue';
  import TextInput from '@/Components/TextInput.vue';
  import PrimaryButton from '@/Components/PrimaryButton.vue';

  import Modal from '@/Components/Modal.vue';

  import EmployeeCard from './Fragments/EmployeeCard.vue';

  const props = defineProps<{
    units: any,
    employees: any,
  }>();

  // console.log(props.paginatedGroups.data)
  const showCreateModal = ref(false);

  // const page = usePage()

  // console.log(page.props);

  const form = useForm({
    name: '',
    email: '',
    cpf: '',
    unit_id: '',
  });

  const formDelete = useForm({});

  const createEmployee = () => {
    form.post(route('employees.store'), {
      preserveScroll: true,
      onSuccess: () => {
        form.reset();
      }
    });
  };

  const deleteEmployee = (groupId: number) => {
    formDelete.delete(route('employees.delete', groupId), {
      preserveScroll: true,
    });
  }
</script>

<template>

  <Head title="Marcas" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
        Colaboradores
      </h2>

      <PrimaryButton @click="() => { showCreateModal = true }">Criar</PrimaryButton>
    </template>

    <div class="py-12">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 md:mx-auto max-w-7xl sm:px-6 lg:px-8">
        <EmployeeCard v-for="(employee, i) of employees.data" :key="i" :employee="employee" @delete="deleteEmployee" />
      </div>
    </div>

    <Modal :show="showCreateModal" @close="() => { showCreateModal = false }">
      <section class="p-8">
        <header class="flex justify-between">
          <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            Criar Colaborador
          </h2>

          <button type="button" @click="() => showCreateModal = false">
            <i class="pi pi-times"></i>
          </button>
        </header>

        <form @submit.prevent="createEmployee" class="mt-6 space-y-6">
          <div>
            <InputLabel for="name" value="Nome" />

            <TextInput id="name" ref="name" v-model="form.name" type="text" class="mt-1 block w-full h-10 rounded-md"
              autocomplete="name" />

            <InputError :message="form.errors.name" class="mt-2" />
          </div>

          <div>
            <InputLabel for="email" value="Email" />

            <TextInput id="email" ref="email" v-model="form.email" type="text" class="mt-1 block w-full h-10 rounded-md"
              autocomplete="email" />

            <InputError :message="form.errors.email" class="mt-2" />
          </div>

          <div>
            <InputLabel for="cpf" value="CPF" />

            <TextInput v-maska data-maska="###.###.###-##" id="cpf" ref="cpf" v-model="form.cpf" type="text"
              class="mt-1 block w-full h-10 rounded-md" autocomplete="cpf" />

            <InputError :message="form.errors.cpf" class="mt-2" />
          </div>

          <div>
            <InputLabel for="unit_id" value="Unidade" />

            <select name="unit_id" id="unit_id" ref="unit_id" v-model="form.unit_id"
              class="mt-1 block w-full h-10 rounded-md" autocomplete="unit_id">
              <option v-for="(unit, i) of units" :key="i" :value="unit.id">{{ unit.trade_name }}</option>
            </select>

            <InputError :message="form.errors.unit_id" class="mt-2" />
          </div>

          <div class="flex items-center gap-4">
            <PrimaryButton :disabled="form.processing">Cadastrar</PrimaryButton>

            <Transition enter-active-class="transition ease-in-out" enter-from-class="opacity-0"
              leave-active-class="transition ease-in-out" leave-to-class="opacity-0">
              <p v-if="form.recentlySuccessful" class="text-sm text-gray-600 dark:text-gray-400">
                Saved.
              </p>
            </Transition>
          </div>
        </form>
      </section>
    </Modal>
  </AuthenticatedLayout>
</template>