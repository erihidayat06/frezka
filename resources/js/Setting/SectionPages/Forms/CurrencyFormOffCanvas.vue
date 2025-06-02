<template>
  <form @submit="formSubmit">
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-fullscreen-md-down">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="offcanvas-title">
              <template v-if="currentId != 0">
                <span>{{ $t('currency.lbl_edit') }}</span>
              </template>
              <template v-else>
                <span>{{ $t('currency.lbl_add') }}</span>
              </template>
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <InputField class="col-md-12" :is-required="true" :label="$t('currency.lbl_currency_name')" placeholder="" v-model="currency_name" :error-message="errors.currency_name" :error-messages="errorMessages['currency_name']"></InputField>
                  <InputField class="col-md-12"  :label="$t('currency.lbl_currency_symbol')" placeholder="" v-model="currency_symbol" :error-message="errors.currency_symbol" :error-messages="errorMessages['currency_symbol']"></InputField>
                  <InputField class="col-md-12" :is-required="true" :label="$t('currency.lbl_currency_code')" placeholder="" v-model="currency_code" :error-message="errors.currency_code" :error-messages="errorMessages['currency_code']"></InputField>
                </div>
                <div class="form-group">
                  <div class="d-flex justify-content-between align-items-center">
                    <label class="form-label" for="category-status">{{ $t('currency.lbl_is_primary') }}</label>
                    <div class="form-check form-switch">
                      <input class="form-check-input" :value="is_primary" :checked="is_primary" name="is_primary" id="is_primary" type="checkbox" v-model="is_primary" />
                    </div>
                  </div>
                </div>

                <h6><b>{{ $t('currency.currency_format') }}</b></h6>

                <div class="form-group">
                  <label class="form-label" for="label">{{ $t('currency.lbl_currency_position') }}</label>
                  <Multiselect
                    v-model="currency_position"
                    :options="[
                      { value: 'left', label: t('messages.lbl_left') },
                      { value: 'right', label: t('messages.lbl_right') },
                      { value: 'left_with_space', label: t('messages.lbl_left_with_space') },
                      { value: 'right_with_space', label: t('messages.lbl_right_with_space') }
                    ]"
                    placeholder=""
                  />
                </div>

                <InputField class="col-md-12" :is-required="true" :label="$t('currency.lbl_thousand_separatorn')" placeholder="" v-model="thousand_separator" :error-message="errors.thousand_separator" :error-messages="errorMessages['thousand_separator']"></InputField>

                <InputField class="col-md-12" :is-required="true" :label="$t('currency.lbl_decimal_separator')" placeholder="" v-model="decimal_separator" :error-message="errors.decimal_separator" :error-messages="errorMessages['decimal_separator']"></InputField>

                <InputField class="col-md-12" :is-required="true" :label="$t('currency.lbl_number_of_decimals')" placeholder="" v-model="no_of_decimal" :error-message="errors.no_of_decimal" :error-messages="errorMessages['no_of_decimal']"></InputField>

                <div class="form-group">
                  <label class="form-label" for="label">{{ $t('currency.lbl_example') }} :- {{ formatCurrencyVue(labelValue, no_of_decimal, decimal_separator, thousand_separator, currency_position, currency_symbol) }}</label>
                </div>
              </div>
            </div>
          </div>
          <div class="border-top">
            <div class="d-grid d-md-flex gap-3 p-3">
              <button class="btn btn-primary d-block">
                <i class="fa-solid fa-floppy-disk"></i>
                {{$t('messages.save')}}
              </button>
              <button class="btn btn-outline-primary d-block" type="button" data-bs-dismiss="modal">
                <i class="fa-solid fa-angles-left"></i>
                {{$t('messages.cancel')}}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import { STORE_URL, EDIT_URL, UPDATE_URL } from '@/vue/constants/currency'
import { useField, useForm } from 'vee-validate'
import { useRequest , useOnModalHide} from '@/helpers/hooks/useCrudOpration'
import * as yup from 'yup'
import InputField from '@/vue/components/form-elements/InputField.vue'
import { useI18n } from 'vue-i18n'

useOnModalHide('exampleModal', () => setFormData(defaultData()))

onMounted(() => {
  // getServiceList()
  // getManagerList()
  setFormData(defaultData())
})


const emit = defineEmits(['onSubmit'])

// props
const props = defineProps({
  id: { type: Number, default: 0 },
  labelValue: 123456789
})

const { getRequest, storeRequest, updateRequest } = useRequest()

const labelValue = ref(1234567.89)

const currentId = ref(props.id)

// Edit Form Or Create Form
watch(
  () => props.id,
  (value) => {
    currentId.value = value
    if (value > 0) {
      getRequest({ url: EDIT_URL, id: value }).then((res) => {
        if (res.status && res.data) {
          setFormData(res.data)
        }
      })
    } else {
      setFormData(defaultData())
    }
  }
)

// Default FORM DATA
const defaultData = () => {
  errorMessages.value = {}
  return {
    currency_name: '',
    currency_symbol: '$',
    currency_code: '',
    currency_position: 'left',
    no_of_decimal: 2,
    thousand_separator: ',',
    decimal_separator: '.',
    is_primary : false,
  }
}
const formatCurrencyVue = window.formatCurrency

//  Reset Form
const setFormData = (data) => {
  resetForm({
    values: {
      currency_name: data.currency_name,
      currency_symbol: data.currency_symbol,
      currency_code: data.currency_code,
      currency_position: data.currency_position,
      no_of_decimal: data.no_of_decimal,
      thousand_separator: data.thousand_separator,
      decimal_separator: data.decimal_separator,
      is_primary: data.is_primary
    }
  })
}

// Reload Datatable, SnackBar Message, Alert, Offcanvas Close
const reset_datatable_close_offcanvas = (res) => {
  if (res.status) {
    window.successSnackbar(res.message)
    bootstrap.Modal.getInstance('#exampleModal').hide()
    setFormData(defaultData())
  } else {
    window.errorSnackbar(res.message)
    errorMessages.value = res.all_message
  }
  emit('onSubmit')
}
const numberRegex = /^\d+$/;
// Validations

const { t } = useI18n();

const validationSchema = yup.object({
  currency_name: yup.string()
    .required(t('messages.currency_name_required'))
    .test('is-string', t('messages.only_strings_allowed'), (value) => !numberRegex.test(value)),
    currency_symbol:  yup.string().required(t('messages.currency_symbol_required')),
    currency_code: yup.string().required(t('messages.currency_code_required')),
    no_of_decimal: yup.string().required(t('messages.no_of_decimal_required')),
    thousand_separator: yup.string().required(t('messages.thousand_separator_required')),
    decimal_separator: yup.string().required(t('messages.decimal_separator_required'))
})

const { handleSubmit, errors, resetForm } = useForm({
  validationSchema
})

const { value: currency_name } = useField('currency_name')
const { value: currency_symbol } = useField('currency_symbol')
const { value: currency_code } = useField('currency_code')
const { value: currency_position } = useField('currency_position')
const { value: no_of_decimal } = useField('no_of_decimal')
const { value: thousand_separator } = useField('thousand_separator')
const { value: decimal_separator } = useField('decimal_separator')
const { value: is_primary } = useField('is_primary')
const errorMessages = ref({})



// Form Submit
const formSubmit = handleSubmit((values) => {
  if (currentId.value > 0) {
    updateRequest({ url: UPDATE_URL, id: currentId.value, body: values }).then((res) => reset_datatable_close_offcanvas(res))
  } else {
    storeRequest({ url: STORE_URL, body: values }).then((res) => reset_datatable_close_offcanvas(res))
  }
})
</script>
