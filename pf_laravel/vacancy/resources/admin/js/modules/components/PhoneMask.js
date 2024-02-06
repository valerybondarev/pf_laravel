import InputMask from "inputmask/lib/inputmask";

export default function phoneMask(input) {
    let defaultMask = '+7 (999) 999-99-99',
        mask = input.getAttribute('phone-mask-value')

    InputMask(mask ?? defaultMask).mask(input);
}