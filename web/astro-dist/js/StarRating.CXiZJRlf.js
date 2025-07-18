import{r as p}from"./index.CVf8TyFT.js";var u={exports:{}},i={};/**
 * @license React
 * react-jsx-runtime.production.min.js
 *
 * Copyright (c) Facebook, Inc. and its affiliates.
 *
 * This source code is licensed under the MIT license found in the
 * LICENSE file in the root directory of this source tree.
 */var d=p,f=Symbol.for("react.element"),m=Symbol.for("react.fragment"),_=Object.prototype.hasOwnProperty,y=d.__SECRET_INTERNALS_DO_NOT_USE_OR_YOU_WILL_BE_FIRED.ReactCurrentOwner,h={key:!0,ref:!0,__self:!0,__source:!0};function c(s,e,r){var t,o={},n=null,l=null;r!==void 0&&(n=""+r),e.key!==void 0&&(n=""+e.key),e.ref!==void 0&&(l=e.ref);for(t in e)_.call(e,t)&&!h.hasOwnProperty(t)&&(o[t]=e[t]);if(s&&s.defaultProps)for(t in e=s.defaultProps,e)o[t]===void 0&&(o[t]=e[t]);return{$$typeof:f,type:s,key:n,ref:l,props:o,_owner:y.current}}i.Fragment=m;i.jsx=c;i.jsxs=c;u.exports=i;var a=u.exports;class x extends p.Component{constructor(e){super(e),this.state={rating:e.initial||0}}componentDidMount(){const e=document.getElementById(this.props.fieldId);e&&e.value&&this.setState({rating:parseInt(e.value)||0})}handleClick=e=>{this.setState({rating:e});const r=document.getElementById(this.props.fieldId);if(r){r.value=e;const t=new Event("input",{bubbles:!0});r.dispatchEvent(t)}};render(){const{max:e=5}=this.props,{rating:r}=this.state;return a.jsx("div",{style:{display:"flex",gap:"0.5rem",fontSize:"1.5rem"},children:Array.from({length:e},(t,o)=>{const n=o+1;return a.jsx("span",{onClick:()=>this.handleClick(n),style:{color:n<=r?"gold":"gray",cursor:"pointer",userSelect:"none"},children:"★"},n)})})}}export{x as default};
