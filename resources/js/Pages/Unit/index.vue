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

  import UnitCard from './Fragments/UnitCard.vue';

  const props = defineProps<{
    brands: any,
    units: any,
  }>();

  // console.log(props.paginatedGroups.data)
  const showCreateModal = ref(false);

  // const page = usePage()

  // console.log(page.props);

  const form = useForm({
    trade_name: '',
    legal_name: '',
    cnpj: '',
    brand_id: '',
  });

  const formDelete = useForm({});

  const createUnit = () => {
    form.post(route('unit.create'), {
      preserveScroll: true,
      onSuccess: () => {
        form.reset();
      },
      onError: () => {
        showCreateModal.value = false;
      }
    });
  };

  const deleteUnit = (groupId: number) => {
    formDelete.delete(route('unit.delete', groupId), {
      preserveScroll: true,
    });
  }
</script>

<template>

  <Head title="Marcas" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
        Unidades
      </h2>

      <PrimaryButton @click="() => { showCreateModal = true }">Criar</PrimaryButton>
    </template>

    <div class="py-12">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 md:mx-auto max-w-7xl sm:px-6 lg:px-8">
        <UnitCard v-for="(unit, i) of units.data" :key="i" :unit="unit" @delete="deleteUnit" />
      </div>
    </div>

    <Modal :show="showCreateModal" @close="() => { showCreateModal = false }">
      <section class="p-8">
        <header class="flex justify-between">
          <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            Criar Unidade
          </h2>

          <button type="button" @click="() => showCreateModal = false">
            <i class="pi pi-times"></i>
          </button>
          <!-- <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            Ensure your account is using a long, random password to stay
            secure.
          </p> -->
        </header>

        <form @submit.prevent="createUnit" class="mt-6 space-y-6">
          <div>
            <InputLabel for="trade_name" value="Nome Comercial" />

            <TextInput id="trade_name" ref="trade_name" v-model="form.trade_name" type="text"
              class="mt-1 block w-full h-10 rounded-md" autocomplete="trade_name" />

            <InputError :message="form.errors.trade_name" class="mt-2" />
          </div>

          <div>
            <InputLabel for="legal_name" value="Nome Legal" />

            <TextInput id="legal_name" ref="legal_name" v-model="form.legal_name" type="text"
              class="mt-1 block w-full h-10 rounded-md" autocomplete="legal_name" />

            <InputError :message="form.errors.legal_name" class="mt-2" />
          </div>

          <div>
            <InputLabel for="cnpj" value="CNPJ" />

            <TextInput v-maska data-maska="##.###.###/####-##" id="cnpj" ref="cnpj" v-model="form.cnpj" type="text" class="mt-1 block w-full h-10 rounded-md"
              autocomplete="cnpj" />

            <InputError :message="form.errors.cnpj" class="mt-2" />
          </div>

          <div>
            <InputLabel for="brand" value="Marca" />

            <select name="brand" id="brand" ref="brand" v-model="form.brand_id"
              class="mt-1 block w-full h-10 rounded-md" autocomplete="name">
              <option v-for="(brand, i) of brands" :key="i" :value="brand.id">{{ brand.name }}</option>
            </select>

            <InputError :message="form.errors.brand_id" class="mt-2" />
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