import{_ as u}from"./_plugin-vue_export-helper-DlAUqK2U.js";import{o,e as n,a as s}from"./app-CGrFRThM.js";const d={props:{modelValue:{type:Number,default:0}},computed:{formattedValue(){return this.formatCurrency(this.modelValue)}},methods:{onInput(r){const e=r.target.value.replace(/\D/g,""),l=parseInt(e||"0",10);this.$emit("update:modelValue",l)},formatCurrency(r){if(r==null)return"R$ 0,00";let e=(r/100).toFixed(2);return e=e.replace(".",","),e=e.replace(/\B(?=(\d{3})+(?!\d))/g,"."),`R$ ${e}`},allowOnlyNumbers(r){const e=["Backspace","Tab","ArrowLeft","ArrowRight"];!(r.key>="0"&&r.key<="9")&&!e.includes(r.key)&&r.preventDefault()}}},m=["value"];function p(r,e,l,c,f,t){return o(),n("div",null,[s("input",{type:"text",value:t.formattedValue,onInput:e[0]||(e[0]=(...a)=>t.onInput&&t.onInput(...a)),onKeydown:e[1]||(e[1]=(...a)=>t.allowOnlyNumbers&&t.allowOnlyNumbers(...a)),placeholder:"R$ 0,00",class:"mt-1 px-3 py-2 w-full border border-gray-300"},null,40,m)])}const b=u(d,[["render",p]]);export{b as I};
