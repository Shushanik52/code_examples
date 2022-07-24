import React from "react";
import { InertiaLink, useForm } from "@inertiajs/inertia-react";
import route from "ziggy-js";

const Create = () => {
    const { data, setData, errors, post } = useForm({
        title: "",
        body: "",
    });

    // @ts-ignore
    function handleSubmit(e) {
        e.preventDefault();
        post(route("posts.store"));
    }

    return (
        <div className="mt-20">
            <div className="container flex flex-col justify-center mx-auto">
                <div>
                    <h1 className="mb-8 text-3xl font-bold">
                        <InertiaLink
                            href={route("posts.index")}
                            className="text-indigo-600 hover:text-indigo-700"
                        >
                            Posts
                        </InertiaLink>
                        <span className="font-medium text-indigo-600"> / </span>
                        Create
                    </h1>
                </div>
                <div className="max-w-6xl p-8 bg-white rounded shadow">
                    <form name="createForm" onSubmit={handleSubmit}>
                        <div className="flex flex-col">
                            <div className="mb-4">
                                <label className="">Title</label>
                                <input
                                    type="text"
                                    className="w-full px-4 py-2"
                                    name="title"
                                    value={data.title}
                                    onChange={(e) =>
                                        setData("title", e.target.value)
                                    }
                                />
                                <span className="text-red-600">
                                    {errors.title}
                                </span>
                            </div>
                            <div className="mb-0">
                                <label className="">Description</label>
                                <textarea
                                    className="w-full rounded"
                                    name="body"
                                    value={data.body}
                                    onChange={(e) =>
                                        setData("body", e.target.value)
                                    }
                                />
                                <span className="text-red-600">
                                    {errors.body}
                                </span>
                            </div>
                        </div>
                        <div className="mt-4">
                            <button
                                type="submit"
                                className="px-6 py-2 font-bold text-white bg-green-500 rounded"
                            >
                                Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    );
};

export default Create;
