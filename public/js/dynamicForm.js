document.addEventListener("DOMContentLoaded",function(){let e=document.getElementById("productType"),t=document.getElementById("specificFields"),i=document.getElementById("product-form"),l=document.getElementById("typeError"),n=document.getElementById("price"),r=document.getElementById("priceError");function d(){let i=e.value;t.innerHTML="",""!==e.value&&(l.style.display="none");let n="";switch(i){case"DVD":n=`
                    <div>
                        <label for="size">Size(MB):</label>
                        <input type="number" id="size" name="size" min="0.01" step="0.01" required>
                        <div id="sizeError" class="error-message" style="display: none; color: red;">Size must be greater than 0.</div>
                    </div>`;break;case"Book":n=`
                    <div>
                        <label for="weight">Weight(KG):</label>
                        <input type="number" id="weight" name="weight" min="0.01" step="0.01" required>
                        <div id="weightError" class="error-message" style="display: none; color: red;">Weight must be greater than 0.</div>
                    </div>`;break;case"Furniture":n=`
                    <div>
                        <label for="height">Height(CM):</label>
                        <input type="number" id="height" name="height" min="0.01" step="0.01" required>
                        <label for="width">Width(CM):</label>
                        <input type="number" id="width" name="width" min="0.01" step="0.01" required>
                        <label for="length">Length(CM):</label>
                        <input type="number" id="length" name="length" min="0.01" step="0.01" required>
                        <div id="dimensionsError" class="error-message" style="display: none; color: red;">All dimensions must be greater than 0.</div>
                    </div>`}t.innerHTML=n}e.addEventListener("change",d),i.addEventListener("submit",function t(i){let d=!0;""===e.value?(l.style.display="block",d=!1):l.style.display="none",0>=parseFloat(n.value)?(r.style.display="block",d=!1):r.style.display="none";let s=document.getElementById("size"),a=document.getElementById("sizeError"),o=document.getElementById("weight"),y=document.getElementById("weightError"),u=document.getElementById("dimensionsError"),m=document.getElementById("height"),g=document.getElementById("width"),p=document.getElementById("length");"DVD"===e.value&&s&&0>=parseFloat(s.value)?(a.style.display="block",d=!1):a&&(a.style.display="none"),"Book"===e.value&&o&&0>=parseFloat(o.value)?(y.style.display="block",d=!1):y&&(y.style.display="none"),"Furniture"===e.value&&(0>=parseFloat(m.value)||0>=parseFloat(g.value)||0>=parseFloat(p.value))?(u.style.display="block",d=!1):u&&(u.style.display="none"),d||i.preventDefault()}),d()});