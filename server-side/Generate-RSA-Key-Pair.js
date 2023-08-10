const crypto = require("crypto");

//Use 2048 bits to generate the keys, the higher the bits the more security, but it is slower and consumes more resources.

const { publicKey, privateKey } = crypto.generateKeyPairSync("rsa", {
  modulusLength: 2048,
});

//Copy and save the keys in .pem files

console.log(
	publicKey.export({
		type: "pkcs1",
		format: "pem",
	}),
	privateKey.export({
		type: "pkcs1",
		format: "pem",
	})
)
