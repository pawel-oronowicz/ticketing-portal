import client from "./client.ts";

export const getEnums = async() => {
    const { data } = await client.get('/enums')
    return data
}