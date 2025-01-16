import{o as c,e as f,r as h,d as N,T as S,b as a,u as o,Z as $,a as t,F as j,g as O,n as x,t as u,h as _,w as p,f as g,l as B}from"./app-CGrFRThM.js";import{_ as C}from"./PrimaryButton-DX3O2YAp.js";import{_ as n,a as d}from"./TextInput-DE4YYkok.js";import{_ as m}from"./InputLabel-WqLUuHue.js";const A=["type"],k={__name:"BlueButton",props:{type:{type:String,default:"submit"}},setup(b){return(r,e)=>(c(),f("button",{type:b.type,class:"inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 focus:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150"},[h(r.$slots,"default")],8,A))}},D=["type"],F={__name:"GreenButton",props:{type:{type:String,default:"submit"}},setup(b){return(r,e)=>(c(),f("button",{type:b.type,class:"inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 focus:bg-green-500 active:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-400 focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150"},[h(r.$slots,"default")],8,D))}},J={class:"min-h-screen bg-gray-100 flex items-center justify-center"},T={class:"w-full max-w-md bg-white p-8 shadow-md rounded-lg py-8"},R={class:"flex items-center justify-between mb-8"},G={key:"step-1"},I={class:"mb-4"},L={class:"mb-4"},M={class:"mb-4"},Z={class:"mb-4"},q={class:"mb-4"},H={class:"flex gap-4 mt-4"},K={key:"step-2"},Q={class:"mb-4"},W={class:"mb-4"},X={class:"mb-4"},Y={class:"mb-4"},ee={class:"mb-4"},se={class:"mb-4"},te={class:"flex gap-4 mt-4"},oe={key:"step-3"},ae={class:"space-y-6"},le={class:"p-4 border border-gray-200 rounded-lg shadow-sm bg-white"},re={class:"space-y-3"},ie={class:"flex justify-between"},ne={class:"text-gray-800"},de={class:"flex justify-between"},me={class:"text-gray-800"},ue={class:"p-4 border border-gray-200 rounded-lg shadow-sm bg-white"},ce={class:"space-y-3"},fe={class:"flex justify-between"},pe={class:"text-gray-800"},ge={class:"flex justify-between"},be={class:"text-gray-800"},_e={class:"flex justify-between"},ye={class:"text-gray-800"},xe={class:"flex justify-between"},we={class:"text-gray-800"},ve={class:"flex justify-between"},Ve={class:"text-gray-800"},je={class:"flex justify-between"},Ce={class:"text-gray-800"},ke={class:"flex gap-4 mt-6"},E=3,Ue={__name:"Register",setup(b){const r=N(1),e=S({first_name:"",last_name:"",email:"",password:"",password_confirmation:"",name:"",cnpj:"",address:"",city:"",state:"",zip_code:"",company_type:"MEI"}),w=()=>{P(r.value)&&r.value++},v=()=>{r.value>1&&r.value--},z=async()=>{if(!e.zip_code){e.errors.zip_code="O CEP é obrigatório.";return}try{const i=await fetch(`https://viacep.com.br/ws/${e.zip_code}/json/`);if(!i.ok)throw new Error("Erro ao buscar CEP");const s=await i.json();s.erro?e.errors.zip_code="CEP não encontrado.":(e.state=s.uf,e.city=s.localidade,e.address=s.logradouro,e.errors.zip_code="")}catch{e.errors.zip_code="Erro ao buscar informações do CEP."}},P=i=>(e.errors={},i===1?(e.first_name||(e.errors.first_name="O primeiro nome é obrigatório."),e.last_name||(e.errors.last_name="O último nome é obrigatório."),e.email||(e.errors.email="O e-mail é obrigatório."),e.password||(e.errors.password="A senha é obrigatória."),e.password!==e.password_confirmation&&(e.errors.password_confirmation="As senhas não correspondem.")):i===2&&(e.name||(e.errors.name="O nome da empresa é obrigatório."),e.cnpj||(e.errors.cnpj="O CNPJ é obrigatório."),e.address||(e.errors.address="O endereço é obrigatório."),e.city||(e.errors.city="A cidade é obrigatória."),e.state||(e.errors.state="O estado é obrigatório."),e.zip_code||(e.errors.zip_code="O CEP é obrigatório.")),Object.keys(e.errors).length===0),V=i=>r.value===i,y=i=>r.value>i,U=()=>{e.post(route("company.store"),{onSuccess:()=>{window.location.href=route("login")}})};return(i,s)=>(c(),f(j,null,[a(o($),{title:"Cadastro | Empresa Pro"}),t("div",J,[t("div",T,[s[29]||(s[29]=t("h1",{class:"text-2xl font-bold text-center text-gray-700 py-3"}," Realizar Cadastro ",-1)),t("div",R,[(c(),f(j,null,O(E,l=>t("div",{key:l,class:"flex items-center space-x-2"},[t("div",{class:x(["flex items-center justify-center w-10 h-10 rounded-full border-2",V(l)?"border-indigo-500 bg-indigo-100":y(l)?"border-green-500 bg-green-100":"border-gray-300 bg-gray-100"])},[t("span",{class:x([V(l)?"text-indigo-600":y(l)?"text-green-600":"text-gray-500","font-bold text-lg"])},u(l),3)],2),l<E?(c(),f("div",{key:0,class:x(["h-1 w-16 bg-gray-300",y(l)&&"bg-green-500"])},null,2)):_("",!0)])),64))]),a(B,{name:"fade",mode:"out-in"},{default:p(()=>[t("div",null,[r.value===1?(c(),f("div",G,[s[12]||(s[12]=t("h2",{class:"text-lg font-semibold text-gray-700 mb-4"}," Dados do Usuário ",-1)),t("div",I,[a(m,{for:"first_name",value:"Primeiro Nome"}),a(n,{id:"first_name",modelValue:o(e).first_name,"onUpdate:modelValue":s[0]||(s[0]=l=>o(e).first_name=l),type:"text",class:"mt-1 block w-full"},null,8,["modelValue"]),a(d,{class:"mt-2",message:o(e).errors.first_name},null,8,["message"])]),t("div",L,[a(m,{for:"last_name",value:"Último Nome"}),a(n,{id:"last_name",modelValue:o(e).last_name,"onUpdate:modelValue":s[1]||(s[1]=l=>o(e).last_name=l),type:"text",class:"mt-1 block w-full"},null,8,["modelValue"]),a(d,{class:"mt-2",message:o(e).errors.last_name},null,8,["message"])]),t("div",M,[a(m,{for:"email",value:"Email"}),a(n,{id:"email",modelValue:o(e).email,"onUpdate:modelValue":s[2]||(s[2]=l=>o(e).email=l),type:"email",class:"mt-1 block w-full"},null,8,["modelValue"]),a(d,{class:"mt-2",message:o(e).errors.email},null,8,["message"])]),t("div",Z,[a(m,{for:"password",value:"Senha"}),a(n,{id:"password",modelValue:o(e).password,"onUpdate:modelValue":s[3]||(s[3]=l=>o(e).password=l),type:"password",class:"mt-1 block w-full"},null,8,["modelValue"]),a(d,{class:"mt-2",message:o(e).errors.password},null,8,["message"])]),t("div",q,[a(m,{for:"password_confirmation",value:"Confirme a Senha"}),a(n,{id:"password_confirmation",modelValue:o(e).password_confirmation,"onUpdate:modelValue":s[4]||(s[4]=l=>o(e).password_confirmation=l),type:"password",class:"mt-1 block w-full"},null,8,["modelValue"]),a(d,{class:"mt-2",message:o(e).errors.password_confirmation},null,8,["message"])]),t("div",H,[a(k,{onClick:w,class:"w-full flex justify-center items-center"},{default:p(()=>s[11]||(s[11]=[g(" Próximo ")])),_:1})])])):_("",!0),r.value===2?(c(),f("div",K,[s[15]||(s[15]=t("h2",{class:"text-lg font-semibold text-gray-700 mb-4"}," Dados da Empresa ",-1)),t("div",Q,[a(m,{for:"name",value:"Nome da Empresa"}),a(n,{id:"name",modelValue:o(e).name,"onUpdate:modelValue":s[5]||(s[5]=l=>o(e).name=l),type:"text",class:"mt-1 block w-full"},null,8,["modelValue"]),a(d,{class:"mt-2",message:o(e).errors.name},null,8,["message"])]),t("div",W,[a(m,{for:"cnpj",value:"CNPJ/CPF"}),a(n,{id:"cnpj",modelValue:o(e).cnpj,"onUpdate:modelValue":s[6]||(s[6]=l=>o(e).cnpj=l),type:"text",class:"mt-1 block w-full"},null,8,["modelValue"]),a(d,{class:"mt-2",message:o(e).errors.cnpj},null,8,["message"])]),t("div",X,[a(m,{for:"zip_code",value:"CEP"}),a(n,{id:"zip_code",modelValue:o(e).zip_code,"onUpdate:modelValue":s[7]||(s[7]=l=>o(e).zip_code=l),type:"text",class:"mt-1 block w-full",onBlur:z},null,8,["modelValue"]),a(d,{class:"mt-2",message:o(e).errors.zip_code},null,8,["message"])]),t("div",Y,[a(m,{for:"state",value:"Estado"}),a(n,{id:"state",modelValue:o(e).state,"onUpdate:modelValue":s[8]||(s[8]=l=>o(e).state=l),type:"text",class:"mt-1 block w-full"},null,8,["modelValue"]),a(d,{class:"mt-2",message:o(e).errors.state},null,8,["message"])]),t("div",ee,[a(m,{for:"city",value:"Cidade"}),a(n,{id:"city",modelValue:o(e).city,"onUpdate:modelValue":s[9]||(s[9]=l=>o(e).city=l),type:"text",class:"mt-1 block w-full"},null,8,["modelValue"]),a(d,{class:"mt-2",message:o(e).errors.city},null,8,["message"])]),t("div",se,[a(m,{for:"address",value:"Endereço"}),a(n,{id:"address",modelValue:o(e).address,"onUpdate:modelValue":s[10]||(s[10]=l=>o(e).address=l),type:"text",class:"mt-1 block w-full"},null,8,["modelValue"]),a(d,{class:"mt-2",message:o(e).errors.address},null,8,["message"])]),t("div",te,[a(C,{onClick:v,class:"w-1/2 flex justify-center items-center"},{default:p(()=>s[13]||(s[13]=[g(" Voltar ")])),_:1}),a(k,{onClick:w,class:"w-1/2 flex justify-center items-center"},{default:p(()=>s[14]||(s[14]=[g(" Próximo ")])),_:1})])])):_("",!0),r.value===3?(c(),f("div",oe,[s[28]||(s[28]=t("h2",{class:"text-2xl font-semibold text-gray-800 mb-6 text-center"}," Confirmação ",-1)),t("div",ae,[t("div",le,[s[18]||(s[18]=t("h3",{class:"text-lg font-semibold text-gray-800 mb-4"}," Dados do Usuário ",-1)),t("ul",re,[t("li",ie,[s[16]||(s[16]=t("span",{class:"font-medium text-gray-600"},"Nome:",-1)),t("span",ne,u(o(e).first_name)+" "+u(o(e).last_name),1)]),t("li",de,[s[17]||(s[17]=t("span",{class:"font-medium text-gray-600"},"Email:",-1)),t("span",me,u(o(e).email),1)])])]),t("div",ue,[s[25]||(s[25]=t("h3",{class:"text-lg font-semibold text-gray-800 mb-4"}," Dados da Empresa ",-1)),t("ul",ce,[t("li",fe,[s[19]||(s[19]=t("span",{class:"font-medium text-gray-600"}," Nome da Empresa: ",-1)),t("span",pe,u(o(e).name),1)]),t("li",ge,[s[20]||(s[20]=t("span",{class:"font-medium text-gray-600"},"CNPJ/CPF:",-1)),t("span",be,u(o(e).cnpj),1)]),t("li",_e,[s[21]||(s[21]=t("span",{class:"font-medium text-gray-600"},"CEP:",-1)),t("span",ye,u(o(e).zip_code),1)]),t("li",xe,[s[22]||(s[22]=t("span",{class:"font-medium text-gray-600"},"Estado:",-1)),t("span",we,u(o(e).state),1)]),t("li",ve,[s[23]||(s[23]=t("span",{class:"font-medium text-gray-600"},"Cidade:",-1)),t("span",Ve,u(o(e).city),1)]),t("li",je,[s[24]||(s[24]=t("span",{class:"font-medium text-gray-600"},"Endereço:",-1)),t("span",Ce,u(o(e).address),1)])])])]),t("div",ke,[a(C,{onClick:v,class:"w-1/2 flex justify-center items-center"},{default:p(()=>s[26]||(s[26]=[g(" Voltar ")])),_:1}),a(F,{onClick:U,class:"w-1/2 flex justify-center items-center"},{default:p(()=>s[27]||(s[27]=[g(" Confirmar ")])),_:1})])])):_("",!0)])]),_:1})])])],64))}};export{Ue as default};
