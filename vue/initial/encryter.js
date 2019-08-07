let cipher = salt => {
    let textToChars = text => text.split('').map(c => c.charCodeAt(0));
    let byteHex = n => ("0" + Number(n).toString(16)).substr(-2);
    let applySaltToChar = code => textToChars(salt).reduce((a, b) => a ^ b, code);

    return text => text.split('')
        .map(textToChars)
        .map(applySaltToChar)
        .map(byteHex)
        .join('');
};

let decipher = salt => {
    let textToChars = text => text.split('').map(c => c.charCodeAt(0));
    let applySaltToChar = code => textToChars(salt).reduce((a, b) => a ^ b, code);
    return encoded => encoded.match(/.{1,2}/g)
        .map(hex => parseInt(hex, 16))
        .map(applySaltToChar)
        .map(charCode => String.fromCharCode(charCode))
        .join('');
};

let jsEncode = {
    dn: 241,
    encode: (s, k) => {
        let enc = "", str;
        // make sure that input is string
        str = s.toString();
        for (let i = 0; i < str.length; i++) {
            // create block
            let a = str.charCodeAt(i);
            // bitwise XOR
            let b = a ^ k;
            enc = enc + String.fromCharCode(b);
        }
        return enc;
    }
};


export const crypter = () => {
    return {
        cipher,
        decipher,
        jsEncode,
    }
};
//
// var e = jsEncode.encode("Hello world!", "123");
// // result 3[Z
// console.log(e);
// var d = jsEncode.encode(e, "123");
// // result Hello world!
// console.log(d);
