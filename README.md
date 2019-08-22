The AICHAIN block chain explorer system.
The project is developed by using php.

This project must run with a AICHAIN block datas parser, which's source code will be added into this project in these days.

The testnet of AICHAIN blockchain explorer is running online:
https://testnetscan.aichain.me/

The mainnet of AICHAIN  blockchain explorer is running online:
https://scan.aichain.me/

Tx query api for an address: all tx records or ERC20 tx records
Sample of all tx records query:
On Testnet:
https://testnetscan.aichain.me/api/v1/gettxs?address=aib135fb747599b830e2110b56e3c76496dc412c54
On Mainnet:
https://scan.aichain.me/api/v1/gettxs?address=aif6b8b4d22ec2ff9303d14ba25336f4391aa16495
Above: all tx records will be returned, the key value is_erc20 will be used to identify ERC20 tx or normal tx

Sample of ERC20 tx records query:
Need two parameters: smart contract address(contract_address) and wallet address(address)

On testnet:
https://testnetscan.aichain.me/api/v1/gettokentxs?contract_address=ai3d3e7162ada3f49a2d371ab524f6618d87328906&address=aib6a9549366e9a8346226f90005043c5932479052
On Mainnet:
https://scan.aichain.me/api/v1/gettokentxs?address=aif6b8b4d22ec2ff9303d14ba25336f4391aa16495&contract_address=ai491d1a667ac51f601582091c1f37cf5b4781d691

Also can query the ERC20 token information of one smart contract by using parameter: contract_address

On Testnet:
https://testnetscan.aichain.me/api/v1/queryerc20tokeninfo?contract_address=ai3d3e7162ada3f49a2d371ab524f6618d87328906
On Mainnet:
https://scan.aichain.me/api/v1/queryerc20tokeninfo?contract_address=ai491d1a667ac51f601582091c1f37cf5b4781d691
