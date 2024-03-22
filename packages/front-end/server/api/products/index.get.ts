import { defineBackendRequestHandler } from "~/server/util/backendRequestHandler";
// import {readBody, getQuery} from "#imports";

export default defineBackendRequestHandler({
    route: "/products",
    method: "GET",
})
